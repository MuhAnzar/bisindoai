"""
Generate A4 PDF from walkthrough.md with rendered Mermaid diagrams.
Uses Playwright (headless Chromium) to render Mermaid JS then print to PDF.
"""
import markdown
import re
import os
from playwright.sync_api import sync_playwright

WALKTHROUGH_PATH = r"C:\Users\Acer\.gemini\antigravity\brain\88efb709-c1db-478a-bd26-1823aef02eae\walkthrough.md"
OUTPUT_PDF = os.path.join(os.path.dirname(os.path.abspath(__file__)), "arsitektur_mode_kalimat.pdf")
TEMP_HTML = os.path.join(os.path.dirname(os.path.abspath(__file__)), "_temp_walkthrough.html")


def md_to_html(md_text: str) -> str:
    """Convert markdown to HTML, extracting mermaid blocks for JS rendering."""
    # Extract mermaid code blocks and replace with div placeholders
    mermaid_blocks = []
    def replace_mermaid(match):
        code = match.group(1).strip()
        idx = len(mermaid_blocks)
        mermaid_blocks.append(code)
        return f'<div class="mermaid" id="mermaid-{idx}">\n{code}\n</div>'

    md_text = re.sub(r'```mermaid\s*\n(.*?)```', replace_mermaid, md_text, flags=re.DOTALL)

    # Convert remaining markdown to HTML
    html_body = markdown.markdown(
        md_text,
        extensions=['tables', 'fenced_code', 'codehilite', 'toc'],
        extension_configs={'codehilite': {'css_class': 'highlight'}}
    )

    return html_body


def build_full_html(body_html: str) -> str:
    """Wrap body HTML in a full page with styles and Mermaid JS."""
    return f"""<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Arsitektur Mode Kalimat - BisindoCNNfi</title>
<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
<style>
    @page {{
        size: A4;
        margin: 20mm 18mm 20mm 18mm;
    }}
    * {{
        box-sizing: border-box;
    }}
    body {{
        font-family: 'Segoe UI', 'Arial', sans-serif;
        font-size: 11pt;
        line-height: 1.6;
        color: #1a1a1a;
        max-width: 100%;
        margin: 0;
        padding: 0;
    }}
    h1 {{
        font-size: 20pt;
        color: #0d47a1;
        border-bottom: 3px solid #0d47a1;
        padding-bottom: 8px;
        margin-top: 0;
        page-break-after: avoid;
    }}
    h2 {{
        font-size: 15pt;
        color: #1565c0;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 6px;
        margin-top: 28px;
        page-break-after: avoid;
    }}
    h3 {{
        font-size: 12pt;
        color: #1976d2;
        margin-top: 16px;
        page-break-after: avoid;
    }}
    p {{
        text-align: justify;
        margin: 6px 0;
    }}
    strong {{
        color: #0d47a1;
    }}
    code {{
        background: #f5f5f5;
        padding: 2px 5px;
        border-radius: 3px;
        font-family: 'Consolas', 'Courier New', monospace;
        font-size: 9.5pt;
        color: #c62828;
    }}
    pre {{
        background: #f8f9fa;
        padding: 12px;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
        overflow-x: auto;
        font-size: 9pt;
    }}
    table {{
        width: 100%;
        border-collapse: collapse;
        margin: 12px 0;
        font-size: 9.5pt;
        page-break-inside: avoid;
    }}
    th {{
        background: #1565c0;
        color: white;
        padding: 8px 10px;
        text-align: left;
        font-weight: 600;
    }}
    td {{
        padding: 6px 10px;
        border-bottom: 1px solid #e0e0e0;
    }}
    tr:nth-child(even) {{
        background: #f5f8ff;
    }}
    tr:hover {{
        background: #e8f0fe;
    }}
    hr {{
        border: none;
        border-top: 2px solid #e0e0e0;
        margin: 24px 0;
    }}
    ul, ol {{
        margin: 6px 0;
        padding-left: 24px;
    }}
    li {{
        margin: 4px 0;
    }}
    .mermaid {{
        text-align: center;
        margin: 16px auto;
        page-break-inside: avoid;
        overflow: visible;
    }}
    .mermaid svg {{
        max-width: 100% !important;
        height: auto !important;
    }}
</style>
</head>
<body>
{body_html}

<script>
    mermaid.initialize({{
        startOnLoad: true,
        theme: 'default',
        flowchart: {{
            useMaxWidth: true,
            htmlLabels: true,
            curve: 'basis'
        }},
        sequence: {{
            useMaxWidth: true,
            wrap: true,
            width: 180
        }},
        themeVariables: {{
            fontSize: '12px'
        }}
    }});
</script>
</body>
</html>"""


def generate_pdf():
    print("[1/4] Reading walkthrough.md...")
    with open(WALKTHROUGH_PATH, 'r', encoding='utf-8') as f:
        md_text = f.read()

    print("[2/4] Converting Markdown to HTML...")
    body_html = md_to_html(md_text)
    full_html = build_full_html(body_html)

    with open(TEMP_HTML, 'w', encoding='utf-8') as f:
        f.write(full_html)
    print(f"    Temporary HTML saved: {TEMP_HTML}")

    print("[3/4] Rendering with Chromium (Mermaid diagrams)...")
    with sync_playwright() as p:
        browser = p.chromium.launch()
        page = browser.new_page()
        page.goto(f'file:///{TEMP_HTML.replace(os.sep, "/")}', wait_until='networkidle')

        # Wait for Mermaid to finish rendering
        page.wait_for_timeout(5000)

        # Check how many mermaid diagrams rendered
        rendered = page.evaluate('document.querySelectorAll(".mermaid svg").length')
        total = page.evaluate('document.querySelectorAll(".mermaid").length')
        print(f"    Mermaid diagrams rendered: {rendered}/{total}")

        print("[4/4] Generating A4 PDF...")
        page.pdf(
            path=OUTPUT_PDF,
            format='A4',
            print_background=True,
            margin={
                'top': '20mm',
                'bottom': '20mm',
                'left': '18mm',
                'right': '18mm'
            },
            display_header_footer=True,
            header_template='<div style="font-size:8px;width:100%;text-align:center;color:#999;">Arsitektur Mode Kalimat - BisindoCNNfi</div>',
            footer_template='<div style="font-size:8px;width:100%;text-align:center;color:#999;">Halaman <span class="pageNumber"></span> dari <span class="totalPages"></span></div>'
        )
        browser.close()

    # Cleanup temp file
    if os.path.exists(TEMP_HTML):
        os.remove(TEMP_HTML)

    print(f"\n{'='*50}")
    print(f"PDF berhasil dibuat: {OUTPUT_PDF}")
    size_kb = os.path.getsize(OUTPUT_PDF) / 1024
    print(f"Ukuran file: {size_kb:.1f} KB")
    print(f"{'='*50}")


if __name__ == "__main__":
    generate_pdf()
