#!/usr/bin/env python3
import tarfile
import os

backup_file = r"f:\youtubeautoid\backups\gucci-yanonlja-net-backup.tar.gz"
extract_path = r"f:\youtubeautoid\backups"

print(f"[*] 압축 해제 중: {backup_file}")
print(f"[*] 대상: {extract_path}")

try:
    with tarfile.open(backup_file, "r:gz") as tar:
        tar.extractall(path=extract_path)
    print("[✓] 압축 해제 완료")
    
    # 디렉토리 구조 출력
    print("\n[*] 디렉토리 구조:")
    for root, dirs, files in os.walk(extract_path):
        level = root.replace(extract_path, '').count(os.sep)
        indent = ' ' * 2 * level
        print(f'{indent}{os.path.basename(root)}/')
        subindent = ' ' * 2 * (level + 1)
        for file in files[:5]:
            print(f'{subindent}{file}')
        if len(files) > 5:
            print(f'{subindent}... 외 {len(files) - 5}개 파일')
        if level > 2:
            break
            
except Exception as e:
    print(f"[✗] 오류: {e}")
