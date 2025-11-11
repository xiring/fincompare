<div style="font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, 'Apple Color Emoji', 'Segoe UI Emoji';">
    <h2 style="margin:0 0 12px 0; font-size:18px;">New contact message</h2>
    <p style="margin:0 0 6px 0;"><strong>Name:</strong> {{ $dto->name }}</p>
    <p style="margin:0 0 6px 0;"><strong>Email:</strong> {{ $dto->email }}</p>
    <p style="margin:12px 0 6px 0;"><strong>Message:</strong></p>
    <div style="white-space:pre-wrap; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:10px;">
        {{ $dto->message }}
    </div>
</div>


