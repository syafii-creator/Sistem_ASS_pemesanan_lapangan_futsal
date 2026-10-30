import os
import subprocess

def render_pdf_from_html():
    temp_html_path = os.path.abspath("scratch/executive_deck.html")
    pdf_out_path = os.path.abspath("LapanganKita_Presentasi_Executive.pdf")

    edge_path = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
    cmd = [
        edge_path,
        "--headless=new",
        "--disable-gpu",
        "--no-pdf-header-footer",
        f"--print-to-pdf={pdf_out_path}",
        temp_html_path
    ]
    subprocess.run(cmd, check=True)
    print(f"Executive PDF generated successfully at: {pdf_out_path}")

if __name__ == "__main__":
    render_pdf_from_html()
