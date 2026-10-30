import os
import re
import subprocess

def strip_emojis(text):
    # Regex to match emojis and specific symbols
    emoji_pattern = re.compile(
        "["
        "\U0001F000-\U0001FAFF"  # Emoticons & symbols
        "\U00002700-\U000027BF"  # Dingbats (✔, ❌, ➔, etc)
        "\U00002600-\U000026FF"  # Misc symbols (⚡, ⚽, ⚠️, etc)
        "\U0000FE00-\U0000FE0F"  # Variation selectors
        "]+",
        flags=re.UNICODE
    )
    
    # Specific targeted replacements for cleaner sentence flow
    text = text.replace("⚡  ", "")
    text = text.replace("⚡ ", "")
    text = text.replace("🔥 ", "")
    text = text.replace("💼 ", "")
    text = text.replace("📊 ", "")
    text = text.replace("💡 ", "")
    text = text.replace("🌟 ", "")
    text = text.replace("🔒 ", "")
    text = text.replace("⚠️ ", "")
    text = text.replace("👤 ", "")
    text = text.replace("🎓 ", "")
    text = text.replace("🛠️ ", "")
    text = text.replace("🛠 ", "")
    text = text.replace("✔ ", "- ")
    text = text.replace("✔", "-")
    text = text.replace("❌ ", "")
    text = text.replace("✅ ", "")
    text = text.replace("⚽ ", "")
    text = text.replace("🤖 ", "")
    text = text.replace("➔", "-->")
    text = text.replace("&gt;", ">")

    # Clean any leftover emojis
    cleaned = emoji_pattern.sub("", text)
    # Clean double spaces caused by emoji removal
    cleaned = re.sub(r' +', ' ', cleaned)
    return cleaned

def clean_files():
    # 1. Clean scratch/executive_deck.html
    html_file = 'scratch/executive_deck.html'
    if os.path.exists(html_file):
        with open(html_file, 'r', encoding='utf-8') as f:
            html = f.read()

        html_clean = strip_emojis(html)

        with open(html_file, 'w', encoding='utf-8') as f:
            f.write(html_clean)
        print("Cleaned emojis from scratch/executive_deck.html")

    # 2. Clean presentation_gg.html
    gg_file = 'presentation_gg.html'
    if os.path.exists(gg_file):
        with open(gg_file, 'r', encoding='utf-8') as f:
            gg = f.read()

        gg_clean = strip_emojis(gg)

        with open(gg_file, 'w', encoding='utf-8') as f:
            f.write(gg_clean)
        print("Cleaned emojis from presentation_gg.html")

    # 3. Render Formal PDF
    edge_path = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
    pdf_out = os.path.abspath("LapanganKita_Presentasi_Executive_Formal.pdf")
    temp_html = os.path.abspath(html_file)

    cmd = [
        edge_path,
        "--headless=new",
        "--disable-gpu",
        "--no-pdf-header-footer",
        f"--print-to-pdf={pdf_out}",
        temp_html
    ]
    subprocess.run(cmd, check=True)
    print(f"Rendered formal PDF without emojis at: {pdf_out}")

if __name__ == '__main__':
    clean_files()
