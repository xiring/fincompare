CSV Product Import Guide

Endpoint

- Admin → POST `/admin/products/import`
- Form fields
  - file: CSV file (required, max 20MB, mimes: csv, txt)
  - delimiter: one of [`,`, `;`, `|`, `\t` or tab] (optional; default `,`)
  - has_header: boolean (optional; default true)

Processing

- File is stored under `storage/app/imports/products/...`
- A background job `ImportProductsJob` is dispatched on the `imports` queue with the file path, delimiter, and header flag

CSV Columns

Required headers (order not strict if parsing by header):

- name — string
- partner_id — integer (existing partner)
- product_category_id — integer (existing category)
- slug — string (optional; unique). If empty, system may generate based on name
- description — string (optional)
- is_featured — boolean (`true`/`false`)
- status — string (`active`/`inactive`)
- attributes — JSON string mapping attribute_id → value

Example

```csv
name,partner_id,product_category_id,slug,description,is_featured,status,attributes
"Cashback Card A",1,1,,"Great for groceries and gas.",true,active,"{\"10\":\"3% cashback\",\"11\":true,\"12\":\"VISA\"}"
"Travel Card B",2,1,travel-card-b,"Earn miles and lounge access.",false,active,"{\"10\":\"1.5% cashback\",\"11\":false,\"12\":\"MASTERCARD\"}"
"Personal Loan C",3,2,personal-loan-c,"Fast approval personal loan.",false,inactive,"{\"20\":\"5.99\",\"21\":36,\"22\":\"No prepayment penalty\"}"
```

Attributes JSON

- Keys are `attribute_id` values from the `attributes` table
- Values should match the attribute data type:
  - data_type=string → string (use `value_text`)
  - data_type=number → number (use `value_number`)
  - data_type=boolean → true/false (use `value_boolean`)
  - data_type=json → JSON object/array (use `value_json`)

Tips

- Use `\t` (or select Tab) for Excel TSV exports; the system normalizes to a tab character
- Ensure referenced partners, categories, and attributes exist before import
- Keep slugs unique; leave blank to auto-generate if your job implementation supports it


