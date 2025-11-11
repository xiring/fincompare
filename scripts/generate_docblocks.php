<?php

/**
 * Simple DocBlock generator for PHP classes and methods.
 *
 * - Scans recursively through provided directories
 * - Adds class and method docblocks where missing
 * - Preserves existing indentation for inserted blocks
 * - Naive signature parsing to list @param and @return
 *
 * Usage:
 *   php scripts/generate_docblocks.php [path1] [path2] ...
 *
 * Notes:
 * - If no paths are provided, the script auto-detects the Laravel project root
 *   (by finding composer.json) and defaults to: <project_root>/src
 * - If a provided path is not absolute, it is resolved relative to the project root.
 */

if (php_sapi_name() !== 'cli') {
	exit(1);
}

/**
 * Find the Laravel project root by walking up until composer.json is found.
 * Starts from the directory containing this script.
 */
function findProjectRoot(): string
{
	$dir = __DIR__;
	while ($dir !== dirname($dir)) {
		if (file_exists($dir . '/composer.json')) {
			return $dir;
		}
		$dir = dirname($dir);
	}
	// Fallback to script directory if not found
	return __DIR__;
}

$projectRoot = findProjectRoot();

if ($argc < 2) {
	$default = $projectRoot . '/src';
	if (!is_dir($default)) {
		fwrite(STDERR, "Could not find default src directory at: {$default}\n");
		exit(1);
	}
	fwrite(STDERR, "No paths provided. Defaulting to: {$default}\n");
	$targets = [$default];
} else {
	$targets = [];
	foreach (array_slice($argv, 1) as $arg) {
		$target = $arg;
		if (!preg_match('#^(/|[A-Za-z]:[\\\\/])#', $arg)) {
			// Resolve relative to project root
			$target = rtrim($projectRoot, '/\\') . DIRECTORY_SEPARATOR . ltrim($arg, '/\\');
		}
		$targets[] = $target;
	}
}

foreach ($targets as $target) {
	if (!is_dir($target)) {
		fwrite(STDERR, "Skip non-directory: {$target}\n");
		continue;
	}
	$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($target));
	/** @var SplFileInfo $file */
	foreach ($rii as $file) {
		if ($file->isDir()) {
			continue;
		}
		if (strtolower($file->getExtension()) !== 'php') {
			continue;
		}
		$path = $file->getPathname();
		// Skip Blade templates
		if (preg_match('/\.blade\.php$/i', $path)) {
			continue;
		}
		$code = file_get_contents($path);
		if ($code === false) {
			fwrite(STDERR, "Failed to read: {$path}\n");
			continue;
		}
		$updated = addDocBlocks($code);
		if ($updated !== $code) {
			file_put_contents($path, $updated);
			echo "Updated: {$path}\n";
		}
	}
}

function addDocBlocks(string $code): string
{
	$lines = preg_split("/\r\n|\n|\r/", $code);
	if ($lines === false) {
		return $code;
	}

	// Remove file-level docblocks to reduce noise
	$lines = removeFileDocblock($lines);

	$lineCount = count($lines);
	$i = 0;

	while ($i < $lineCount) {
		$line = $lines[$i];

		// Class/interface/trait
		if (preg_match('/^(\s*)(final\s+|abstract\s+)?(class|interface|trait)\s+([A-Za-z_\x80-\xff][A-Za-z0-9_\x80-\xff]*)/u', $line, $m)) {
			// Normalize any stacked docblocks immediately above
			[$lines, $i, $lineCount] = collapseDocblocksBefore($lines, $i, $lineCount);
			$indent = $m[1];
			$name = $m[4];
			$kind = $m[3];
			// Normalize existing class docblock and ensure a single @package
			[$lines, $i, $lineCount] = cleanupClassDocblock($lines, $i, $lineCount, true);
			$hasDoc = hasDocblockImmediatelyAbove($lines, $i);
			if (!$hasDoc) {
				$ns = findNamespace($lines);
				$summary = generateClassSummary($name, $kind);
				$doc = [];
				$doc[] = "{$indent}/**";
				$doc[] = "{$indent} * {$summary}";
				if ($ns !== null) {
					$doc[] = "{$indent} *";
					$doc[] = "{$indent} * @package {$ns}";
				}
				$doc[] = "{$indent} */";
				array_splice($lines, $i, 0, $doc);
				$added = count($doc);
				$i += $added;
				$lineCount += $added;
			}
			$i++;
			continue;
		}

		// Function/method
		if (preg_match('/^(\s*)(public|protected|private)?\s*(static\s+)?function\s+&?\s*([A-Za-z_\x80-\xff][A-Za-z0-9_\x80-\xff]*)\s*\((.*)$/u', $line, $m)) {
			// Normalize any stacked docblocks immediately above
			[$lines, $i, $lineCount] = collapseDocblocksBefore($lines, $i, $lineCount);
			$indent = $m[1];
			$fnName = $m[4];
			// Collect signature lines until we hit the end of params ')'
			$sigStart = $i;
			$sig = $line;
			$j = $i + 1;
			$paren = substr_count($line, '(') - substr_count($line, ')');
			while ($j < $lineCount && $paren > 0) {
				$sig .= "\n" . $lines[$j];
				$paren += substr_count($lines[$j], '(') - substr_count($lines[$j], ')');
				$j++;
			}
			// Scan forward a bit for return type if on next line like "): Type"
			$afterParamsLineIdx = $j - 1;
			$returnType = parseReturnTypeFromSignature($sig);
			if ($returnType === null) {
				// Try to see if the next line has ": Type"
				if ($afterParamsLineIdx + 1 < $lineCount) {
					if (preg_match('/^\s*:\s*([?\\\\A-Za-z0-9_\x80-\xff\[\]|\\\\]+)/u', $lines[$afterParamsLineIdx + 1], $rm)) {
						$returnType = trim($rm[1]);
					}
				}
			}
			$params = parseParamsFromSignature($sig);
			// Lightweight body inspection to refine return type
			$bodyPreview = '';
			if ($afterParamsLineIdx + 1 < $lineCount) {
				$bodyPreview = implode("\n", array_slice($lines, $afterParamsLineIdx + 1, min(40, $lineCount - ($afterParamsLineIdx + 1))));
			}
			$refinedReturn = refineReturnTypeByBody($returnType, $fnName, $bodyPreview);

			// Cleanup existing method docblock if unnecessary
			[$lines, $i, $lineCount] = cleanupMethodDocblock($lines, $i, $lineCount, $params, $returnType ?? $refinedReturn, $fnName);

			$hasDoc = hasDocblockImmediatelyAbove($lines, $i);
			// Decide whether a new docblock is necessary
			$untypedParams = array_values(array_filter($params, fn($p) => ($p['type'] ?? 'mixed') === 'mixed'));
			$hasDeclaredReturn = ($returnType !== null);
			$shouldAdd = false;
			$tags = [];
			if (!empty($untypedParams)) {
				$shouldAdd = true;
				foreach ($untypedParams as $p) {
					$tags[] = ['param', 'mixed', $p['name']];
				}
			}
			if (!$hasDeclaredReturn && $fnName !== '__construct') {
				$shouldAdd = true;
				$tags[] = ['return', ($refinedReturn ?? 'mixed')];
			}
			if ($shouldAdd && !$hasDoc) {
				$doc = [];
				$doc[] = "{$indent}/**";
				$summaryName = generateMethodSummary($fnName);
				$doc[] = "{$indent} * {$summaryName}";
				if (!empty($tags)) {
					$doc[] = "{$indent} *";
					foreach ($tags as $t) {
						if ($t[0] === 'param') {
							$doc[] = "{$indent} * @param {$t[1]} {$t[2]}";
						} elseif ($t[0] === 'return') {
							$doc[] = "{$indent} * @return {$t[1]}";
						}
					}
				}
				$doc[] = "{$indent} */";
				array_splice($lines, $i, 0, $doc);
				$added = count($doc);
				$i += $added;
				$lineCount += $added;
			}
			$i++;
			continue;
		}

		$i++;
	}

	return implode("\n", $lines);
}

function removeFileDocblock(array $lines): array
{
	$phpIdx = -1;
	for ($k = 0; $k < min(8, count($lines)); $k++) {
		if (preg_match('/^\s*<\?php\b/', $lines[$k])) {
			$phpIdx = $k;
			break;
		}
	}
	if ($phpIdx === -1) return $lines;
	$idx = $phpIdx + 1;
	if (isset($lines[$idx]) && preg_match('/^\s*declare\s*\(\s*strict_types\s*=\s*1\s*\)\s*;/', $lines[$idx])) {
		$idx++;
	}
	// Skip blank lines
	while ($idx < count($lines) && trim($lines[$idx]) === '') $idx++;
	// If next token is a docblock, remove it
	if ($idx < count($lines) && preg_match('/^\s*\/\*\*/', $lines[$idx])) {
		$start = $idx;
		$end = $start;
		while ($end < count($lines) && strpos($lines[$end], '*/') === false) $end++;
		if ($end < count($lines)) {
			array_splice($lines, $start, $end - $start + 1);
		}
	}
	return $lines;
}

function hasDocblockImmediatelyAbove(array $lines, int $idx): bool
{
	$k = $idx - 1;
	while ($k >= 0 && trim($lines[$k]) === '') {
		$k--;
	}
	if ($k < 0) return false;
	// If we're inside or at the end of a docblock, walk back to its start
	if (strpos($lines[$k], '*/') !== false || preg_match('/^\s*\*/', $lines[$k])) {
		while ($k >= 0) {
			if (preg_match('/^\s*\/\*\*/', $lines[$k])) {
				return true;
			}
			// stop if we hit non-comment
			if (!preg_match('/^\s*\*|^\s*\/\*\*|^\s*\*\//', $lines[$k]) && trim($lines[$k]) !== '') {
				break;
			}
			$k--;
		}
	}
	// Or the immediate previous non-empty line is the start
	return preg_match('/^\s*\/\*\*/', $lines[$k]) === 1;
}

function collapseDocblocksBefore(array $lines, int $idx, int $lineCount): array
{
	$blocks = [];
	$k = $idx - 1;
	// Skip trailing empty lines
	while ($k >= 0 && trim($lines[$k]) === '') $k--;
	// Collect consecutive docblocks immediately above this index
	while ($k >= 0) {
		// If current line is inside or at end of a docblock, find its start
		if (strpos($lines[$k], '*/') !== false || preg_match('/^\s*\*/', $lines[$k]) || preg_match('/^\s*\/\*\*/', $lines[$k])) {
			$end = $k;
			// Move up to find '/**'
			while ($k >= 0 && !preg_match('/^\s*\/\*\*/', $lines[$k])) {
				// break if encounter non-comment content
				if (!preg_match('/^\s*\*|^\s*\*\//', $lines[$k]) && trim($lines[$k]) !== '') {
					break 2;
				}
				$k--;
			}
			if ($k >= 0 && preg_match('/^\s*\/\*\*/', $lines[$k])) {
				$start = $k;
				$blocks[] = [$start, $end];
				$k = $start - 1;
				// Skip whitespace between stacked blocks
				while ($k >= 0 && trim($lines[$k]) === '') $k--;
				continue;
			}
			break;
		}
		break;
	}
	if (count($blocks) <= 1) {
		return [$lines, $idx, $lineCount];
	}
	// Keep only the last block (closest to the declaration)
	// Remove earlier ones from the code
	// Blocks collected from bottom up; reverse to remove from top to avoid shifting
	rsort($blocks);
	// The last in collected array is the closest; we want to remove all except the last (index 0 after rsort gives highest start)
	$toKeep = array_shift($blocks); // keep the first after rsort (highest indices)
	$removedTotal = 0;
	foreach ($blocks as [$start, $end]) {
		$len = $end - $start + 1;
		array_splice($lines, $start, $len);
		$removedTotal += $len;
		$idx -= ($start < $idx) ? $len : 0;
		$lineCount -= $len;
	}
	return [$lines, $idx, $lineCount];
}

function cleanupClassDocblock(array $lines, int $idx, int $lineCount, bool $ensurePackage = false): array
{
	// If there is a docblock above, remove any @package lines to avoid duplication
	$k = $idx - 1;
	while ($k >= 0 && trim($lines[$k]) === '') $k--;
	if ($k < 0) return [$lines, $idx, $lineCount];
	// Find start of docblock
	$end = $k;
	while ($k >= 0 && strpos($lines[$k], '/**') === false) {
		if (trim($lines[$k]) !== '' && !preg_match('/^\s*\*/', $lines[$k])) {
			return [$lines, $idx, $lineCount];
		}
		$k--;
	}
	if ($k < 0) return [$lines, $idx, $lineCount];
	$start = $k;
	// Remove any lines containing @package
	$removed = 0;
	for ($i = $end; $i >= $start; $i--) {
		if (strpos($lines[$i], '@package') !== false) {
			array_splice($lines, $i, 1);
			$removed++;
			if ($i < $idx) $idx--;
			$lineCount--;
		}
	}
	// Optionally ensure a single @package right before closing */
	if ($ensurePackage) {
		$ns = findNamespace($lines);
		if ($ns !== null) {
			// find indentation from start line
			if (preg_match('/^(\s*)\/\*\*/', $lines[$start], $mIndent)) {
				$indent = $mIndent[1];
			} else {
				$indent = '';
			}
			// find position of closing */
			$close = $start;
			while ($close <= $end && strpos($lines[$close], '*/') === false) {
				$close++;
			}
			if ($close <= $end) {
				// Insert blank doc line if previous content isn't a blank doc line
				$insert = [];
				// Avoid duplicate blank line if one already just before */
				if ($close - 1 >= $start + 1 && trim(preg_replace('/^\s*\*\s?/', '', $lines[$close - 1])) !== '') {
					$insert[] = "{$indent} *";
				}
				$insert[] = "{$indent} * @package {$ns}";
				array_splice($lines, $close, 0, $insert);
				$delta = count($insert);
				if ($close < $idx) $idx += $delta;
				$lineCount += $delta;
				$end += $delta;
			}
		}
	}
	return [$lines, $idx, $lineCount];
}

function cleanupMethodDocblock(array $lines, int $idx, int $lineCount, array $params, ?string $retType, string $fnName): array
{
	// If there is a docblock above the method and it is unnecessary per rules, remove it.
	$k = $idx - 1;
	while ($k >= 0 && trim($lines[$k]) === '') $k--;
	if ($k < 0) return [$lines, $idx, $lineCount];
	$end = $k;
	// Not a docblock start/end? skip
	if (strpos($lines[$end], '*/') === false && !preg_match('/^\s*\/\*\*/', $lines[$end])) {
		return [$lines, $idx, $lineCount];
	}
	// Find start
	while ($k >= 0 && strpos($lines[$k], '/**') === false) {
		if (!preg_match('/^\s*\*/', $lines[$k]) && trim($lines[$k]) !== '') {
			return [$lines, $idx, $lineCount];
		}
		$k--;
	}
	if ($k < 0 || strpos($lines[$k], '/**') === false) {
		return [$lines, $idx, $lineCount];
	}
	$start = $k;
	$inner = [];
	for ($i = $start + 1; $i <= $end - 1; $i++) {
		$line = $lines[$i];
		$line = preg_replace('/^\s*\*\s?/', '', $line);
		$inner[] = trim($line);
	}
	$hasParamTag = false;
	$hasReturnTag = false;
	$docParamNames = [];
	$docReturnType = null;
	foreach ($inner as $l) {
		if (preg_match('/^@param\s+([^\s]+)\s+(\$[A-Za-z0-9_]+)/', $l, $m)) {
			$hasParamTag = true;
			$docParamNames[] = $m[2];
		}
		if (preg_match('/^@return\s+([^\s]+)/', $l, $m)) {
			$hasReturnTag = true;
			$docReturnType = $m[1];
		}
	}
	$allParamsTyped = true;
	foreach ($params as $p) {
		if (($p['type'] ?? 'mixed') === 'mixed') {
			$allParamsTyped = false;
			break;
		}
	}
	// Removal rules:
	// - If all params are typed and either no @return or @return matches declared return (or constructor), remove docblock
	// - Also remove if docblock only contains a summary (no tags)
	$remove = false;
	if ($allParamsTyped) {
		if (!$hasParamTag && (!$hasReturnTag || $retType !== null)) {
			$remove = true;
		}
		if ($hasReturnTag && $retType !== null && strtolower((string)$docReturnType) === strtolower((string)$retType)) {
			$remove = true;
		}
	}
	if (!$hasParamTag && !$hasReturnTag) {
		$remove = true;
	}
	// Never keep constructor docblocks if empty of useful tags
	if ($fnName === '__construct' && (!$hasParamTag || $allParamsTyped)) {
		$remove = true;
	}
	if ($remove) {
		array_splice($lines, $start, $end - $start + 1);
		$delta = ($end - $start + 1);
		if ($start < $idx) $idx -= $delta;
		$lineCount -= $delta;
	}
	return [$lines, $idx, $lineCount];
}

function ensureFileDocblock(array $lines): array
{
	// Find insertion index: after opening <?php and optional declare(strict_types=1);
	$phpIdx = -1;
	for ($k = 0; $k < min(5, count($lines)); $k++) {
		if (preg_match('/^\s*<\?php\b/', $lines[$k])) {
			$phpIdx = $k;
			break;
		}
	}
	if ($phpIdx === -1) {
		return $lines;
	}
	$insertIdx = $phpIdx + 1;
	if (isset($lines[$insertIdx]) && preg_match('/^\s*declare\s*\(\s*strict_types\s*=\s*1\s*\)\s*;/', $lines[$insertIdx])) {
		$insertIdx++;
	}
	// Check if a file-level docblock already exists before namespace
	$nextNonEmpty = $insertIdx;
	while ($nextNonEmpty < count($lines) && trim($lines[$nextNonEmpty]) === '') {
		$nextNonEmpty++;
	}
	if ($nextNonEmpty < count($lines) && preg_match('/^\s*\/\*\*/', $lines[$nextNonEmpty])) {
		return $lines; // already has a docblock
	}
	$ns = findNamespace($lines);
	[$className, $classKind] = findFirstClassLike($lines);
	$summary = $className ? generateFileSummary($className, $classKind) : 'This file is part of the application.';
	$doc = [];
	$doc[] = "/**";
	$doc[] = " * {$summary}";
	if ($ns !== null) {
		$doc[] = " *";
		$doc[] = " * @package {$ns}";
	}
	$doc[] = " */";
	array_splice($lines, $insertIdx, 0, $doc);
	return $lines;
}

function findPreviousNonEmptyLine(array $lines, int $idx): int
{
	for ($k = $idx; $k >= 0; $k--) {
		if (trim($lines[$k]) === '') {
            // keep going
		} else {
			// If previous is inline comment or closing block comment line, still consider presence if docblock starts earlier
			return $k;
		}
	}
	return -1;
}

function findNamespace(array $lines): ?string
{
	foreach ($lines as $l) {
		if (preg_match('/^\s*namespace\s+([^;]+);/', $l, $m)) {
			return trim($m[1]);
		}
	}
	return null;
}

function findFirstClassLike(array $lines): array
{
	foreach ($lines as $l) {
		if (preg_match('/^\s*(final\s+|abstract\s+)?(class|interface|trait)\s+([A-Za-z_\x80-\xff][A-Za-z0-9_\x80-\xff]*)/u', $l, $m)) {
			return [$m[3], $m[2]];
		}
	}
	return [null, null];
}

function parseParamsFromSignature(string $signature): array
{
	// Extract content between first '(' and its matching ')'
	$start = strpos($signature, '(');
	if ($start === false) {
		return [];
	}
	$depth = 1;
	$i = $start + 1;
	$len = strlen($signature);
	$paramStr = '';
	while ($i < $len && $depth > 0) {
		$ch = $signature[$i];
		if ($ch === '(') $depth++;
		if ($ch === ')') $depth--;
		if ($depth > 0) $paramStr .= $ch;
		$i++;
	}
	if (trim($paramStr) === '') {
		return [];
	}
	// Split top-level params (ignore commas inside default arrays)
	$params = [];
	$current = '';
	$depthBrackets = 0;
	$depthParens = 0;
	$inString = false;
	$stringChar = '';
	for ($i = 0; $i < strlen($paramStr); $i++) {
		$c = $paramStr[$i];
		if ($inString) {
			$current .= $c;
			if ($c === $stringChar && ($i === 0 || $paramStr[$i-1] !== '\\')) {
				$inString = false;
			}
			continue;
		}
		if ($c === '"' || $c === "'") {
			$inString = true;
			$stringChar = $c;
			$current .= $c;
			continue;
		}
		if ($c === '(') $depthParens++;
		if ($c === ')') $depthParens--;
		if ($c === '[') $depthBrackets++;
		if ($c === ']') $depthBrackets--;
		if ($c === ',' && $depthBrackets === 0 && $depthParens === 0) {
			$params[] = trim($current);
			$current = '';
		} else {
			$current .= $c;
		}
	}
	if (trim($current) !== '') {
		$params[] = trim($current);
	}
	$result = [];
	foreach ($params as $p) {
		// Pattern: "[?Type] [&]?$name [= default]"
		if (preg_match('/^(?:(?P<type>[^$&=]+)\s+)?(?:&\s*)?\$(?P<name>[A-Za-z_\x80-\xff][A-Za-z0-9_\x80-\xff]*)/u', $p, $m)) {
			$type = isset($m['type']) ? trim($m['type']) : null;
			if ($type !== null) {
				// Normalize union types spacing
				$type = preg_replace('/\s*\|\s*/', '|', $type);
			}
			$result[] = [
				'name' => '$' . $m['name'],
				'type' => $type ?: 'mixed',
			];
		}
	}
	return $result;
}

function parseReturnTypeFromSignature(string $signature): ?string
{
	// Find colon after the end of params
	$after = strpos($signature, ')', strpos($signature, '(') ?: 0);
	if ($after === false) return null;
	$tail = substr($signature, $after + 1);
	if (preg_match('/^\s*:\s*([?\\\\A-Za-z0-9_\x80-\xff\[\]|\\\\]+)/u', $tail, $m)) {
		return trim($m[1]);
	}
	return null;
}

function generateClassSummary(string $name, string $kind): string
{
	$human = preg_replace('/([a-z\d])([A-Z])/u', '$1 $2', $name);
	$human = trim($human);
	// Heuristics by suffix
	$suffixMap = [
		'Controller' => 'controller',
		'ServiceProvider' => 'service provider',
		'Repository' => 'repository',
		'Interface' => 'interface',
		'Request' => 'form request',
		'Action' => 'application action',
		'Job' => 'job',
		'Policy' => 'policy',
		'Entity' => 'domain entity',
		'DTO' => 'data transfer object',
		'Event' => 'event',
		'Listener' => 'event listener',
		'Middleware' => 'middleware',
		'Command' => 'console command',
		'Exception' => 'exception',
		'Factory' => 'factory',
		'Composer' => 'view composer',
	];
	$label = strtolower($kind);
	foreach ($suffixMap as $suffix => $l) {
		if (str_ends_with($name, $suffix)) {
			$label = $l;
			break;
		}
	}
	return "{$name} {$label}.";
}

function generateFileSummary(string $className, ?string $kind): string
{
	$label = $kind ? strtolower($kind) : 'class';
	// map by suffix similar to class summary
	$summary = generateClassSummary($className, $kind ?? 'class');
	return "This file contains the {$summary}";
}

function generateMethodSummary(string $fnName): string
{
	if ($fnName === '__construct') {
		return 'Constructor.';
	}
	$map = [
		'index' => 'Display a listing of the resource.',
		'create' => 'Show the form for creating a new resource.',
		'store' => 'Store a newly created resource in storage.',
		'show' => 'Display the specified resource.',
		'edit' => 'Show the form for editing the specified resource.',
		'update' => 'Update the specified resource in storage.',
		'destroy' => 'Remove the specified resource from storage.',
		'authorize' => 'Determine if the user is authorized.',
		'rules' => 'Get the validation rules that apply to the request.',
		'handle' => 'Handle the job or request.',
	];
	$lower = strtolower($fnName);
	if (isset($map[$lower])) {
		return $map[$lower];
	}
	$human = preg_replace('/([a-z\d])([A-Z])/u', '$1 $2', $fnName);
	$human = ucfirst(strtolower(trim($human)));
	return "Handle {$human}.";
}

function refineReturnTypeByBody(?string $declared, string $fnName, string $body): ?string
{
	if ($fnName === '__construct') {
		return 'void';
	}
	if ($declared !== null) {
		return $declared;
	}
	// Quick heuristics
	if (preg_match('/return\s+response\(\)\s*->\s*json\(/', $body)) {
		return '\\Illuminate\\Http\\JsonResponse';
	}
	if (preg_match('/return\s+redirect\(/', $body) || preg_match('/return\s+back\(/', $body)) {
		return '\\Illuminate\\Http\\RedirectResponse';
	}
	if (preg_match('/return\s+view\(/', $body) || preg_match('/return\s+view\(\)\s*->/', $body) || preg_match('/return\s+view\(\)\s*->\s*file\(/', $body) || preg_match('/return\s+view\(\)\s*->\s*make\(/', $body) || preg_match('/return\s+view\(\)\s*->\s*first\(/', $body) || preg_match('/return\s+view\(\)\s*->\s*render\(/', $body)) {
		return '\\Illuminate\\Contracts\\View\\View|\\Illuminate\\Contracts\\View\\Factory|\\Illuminate\\Foundation\\Application';
	}
	if (preg_match('/function\s+rules\s*\(/', $body) || preg_match('/return\s*\[/', $body)) {
		// Common for request::rules
		if (stripos($fnName, 'rules') !== false) {
			return 'array';
		}
	}
	if (stripos($fnName, 'authorize') !== false) {
		return 'bool';
	}
	return null;
}


