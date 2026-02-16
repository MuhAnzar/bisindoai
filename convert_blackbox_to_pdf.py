"""
Convert Black Box Testing Markdown to PDF
Simple script using markdown + weasyprint
"""
import markdown
from weasyprint import HTML
import os

# File paths
md_file = r"c:\Users\Acer\.gemini\antigravity\brain\04405138-fc8d-4595-91d9-127e58974073\black_box_testing.md"
pdf_file = r"c:\Users\Acer\.gemini\antigravity\brain\04405138-fc8d-4595-91d9-127e58974073\black_box_testing.pdf"

print("=" * 70)
print("Converting Black Box Testing Documentation to PDF")
print("=" * 70)

try:
    # Read markdown file
    print(f"\n[1/3] Reading: {os.path.basename(md_file)}")
    with open(md_file, 'r', encoding='utf-8') as f:
        md_content = f.read()
    
    # Convert markdown to HTML
    print("[2/3] Converting MD to HTML...")
    html_body = markdown.markdown(md_content, extensions=['tables'])
    
    # Create styled HTML
    styled_html = f"""
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Tabel Pengujian Black Box - BisindoCNNfi</title>
        <style>
            body {{
                font-family: 'Segoe UI', 'Arial', sans-serif;
                margin: 2cm;
                line-height: 1.6;
                color: #333;
            }}
            h1 {{
                color: #2c3e50;
                border-bottom: 3px solid #3498db;
                padding-bottom: 10px;
                margin-top: 30px;
            }}
            h2 {{
                color: #34495e;
                border-bottom: 2px solid #95a5a6;
                padding-bottom: 5px;
                margin-top: 25px;
            }}
            h3 {{
                color: #7f8c8d;
                margin-top: 20px;
            }}
            table {{
                border-collapse: collapse;
                width: 100%;
                margin: 20px 0;
                font-size: 9px;
                page-break-inside: auto;
            }}
            thead {{
                display: table-header-group;
            }}
            tr {{
                page-break-inside: avoid;
                page-break-after: auto;
            }}
            th {{
                background-color: #3498db;
                color: white;
                padding: 10px 6px;
                text-align: left;
                border: 1px solid #2980b9;
                font-weight: bold;
            }}
            td {{
                padding: 8px 6px;
                border: 1px solid #bdc3c7;
                vertical-align: top;
            }}
            tr:nth-child(even) {{
                background-color: #ecf0f1;
            }}
            code {{
                background-color: #f4f4f4;
                padding: 2px 5px;
                border-radius: 3px;
                font-family: 'Courier New', monospace;
                font-size: 8px;
            }}
            ul, ol {{
                margin: 5px 0;
                padding-left: 20px;
            }}
            li {{
                margin: 3px 0;
            }}
            @page {{
                size: A4;
                margin: 1.5cm;
                @bottom-right {{
                    content: counter(page) "/" counter(pages);
                    font-size: 9px;
                    color: #7f8c8d;
                }}
            }}
            .page-break {{
                page-break-after: always;
            }}
        </style>
    </head>
    <body>
        {html_body}
    </body>
    </html>
    """
    
    # Convert HTML to PDF
    print(f"[3/3] Generating PDF: {os.path.basename(pdf_file)}")
    HTML(string=styled_html).write_pdf(pdf_file)
    
    print("\n" + "=" * 70)
    print("✓ SUCCESS! PDF created successfully!")
    print("=" * 70)
    print(f"\nOutput: {pdf_file}")
    print(f"Size: {os.path.getsize(pdf_file) / 1024:.2f} KB")
    
except Exception as e:
    print("\n" + "=" * 70)
    print("✗ ERROR!")
    print("=" * 70)
    print(f"\nError: {str(e)}")
    print("\nTroubleshooting:")
    print("1. Pastikan library terinstall: pip install markdown weasyprint")
    print("2. Pastikan file markdown ada di path yang benar")
    raise
