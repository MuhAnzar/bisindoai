
import markdown
from xhtml2pdf import pisa
import os

# Define input and output paths
input_md_path = r"C:\Users\Acer\.gemini\antigravity\brain\671f03f9-9572-4255-8bb5-a7af131e1417\testing_report.md"
output_pdf_path = r"C:\Users\Acer\.gemini\antigravity\brain\671f03f9-9572-4255-8bb5-a7af131e1417\testing_report.pdf"
output_html_path = r"C:\Users\Acer\.gemini\antigravity\brain\671f03f9-9572-4255-8bb5-a7af131e1417\testing_report.html"

# Enhanced CSS for better table handling
css = """
<style>
    @page {
        size: A4 landscape;
        margin: 1.5cm;
    }
    body {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 9pt; /* Smaller font for table fit */
        line-height: 1.3;
    }
    h1 {
        font-size: 16pt;
        color: #0f766e;
        border-bottom: 2px solid #0f766e;
        padding-bottom: 5px;
        margin-bottom: 15px;
    }
    h2 {
        font-size: 13pt;
        color: #0f766e;
        margin-top: 15px;
        margin-bottom: 8px;
        border-bottom: 1px solid #ddd;
    }
    table {
        width: 100%;
        border: 0.5px solid #999;
        border-collapse: collapse;
        margin-bottom: 15px;
    }
    th {
        background-color: #f0fdfa;
        color: #0d9488;
        font-weight: bold;
        border: 0.5px solid #999;
        padding: 5px;
        font-size: 8pt;
        vertical-align: top;
    }
    td {
        border: 0.5px solid #999;
        padding: 5px;
        font-size: 8pt;
        vertical-align: top;
    }
    /* Specific column adjustments could be added here if needed, but Landscape should solve most issues */
    tr:nth-child(even) {
        background-color: #f9fafb;
    }
    p {
        margin-bottom: 10px;
    }
    code {
        background-color: #f1f5f9;
        padding: 2px 4px;
        border-radius: 4px;
        font-family: Courier New, monospace;
    }
</style>
"""

def convert_to_pdf(source_html, output_filename):
    result_file = open(output_filename, "w+b")
    pisa_status = pisa.CreatePDF(source_html, dest=result_file)
    result_file.close()
    return pisa_status.err

# Read Markdown
with open(input_md_path, "r", encoding="utf-8") as f:
    text = f.read()

# Convert to HTML (with extra extensions for better table support)
html_content = markdown.markdown(text, extensions=['tables', 'fenced_code'])

# Wrap in Full HTML
full_html = f"""
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Testing Report</title>
    {css}
</head>
<body>
    {html_content}
</body>
</html>
"""

# Save HTML (Fallback)
with open(output_html_path, "w", encoding="utf-8") as f:
    f.write(full_html)
print(f"HTML version created at: {output_html_path}")

# Convert to PDF
if convert_to_pdf(full_html, output_pdf_path):
    print("PDF generation failed!")
else:
    print(f"PDF successfully created at: {output_pdf_path}")
