#!/usr/bin/env python3
"""
Script de stéganographie automatisée pour le projet de phishing éducatif
Utilise PIL (Pillow) et Steghide

Usage:
    python3 steganography.py --image input.png --output output.png --sender 1
    python3 steganography.py --extract output.png --output extracted.txt --password test
"""

import argparse
import os
import sys
import subprocess
import tempfile
from pathlib import Path

try:
    from PIL import Image
except ImportError:
    print("PIL non installé. Installez avec: pip install pillow")
    sys.exit(1)


class SteganographyHelper:
    """Utilitaire pour la stéganographie éducative"""
    
    BASE_URL = "http://localhost/messagerie"
    
    @staticmethod
    def generate_payload(sender_id):
        """Génère le payload JavaScript à cacher"""
        payload = f'<script src="{SteganographyHelper.BASE_URL}/codes/assets/popup.js?sender={sender_id}"></script>'
        return payload
    
    @staticmethod
    def encode_with_steghide(image_path, text, output_path, password=""):
        """Encode le texte dans une image avec Steghide"""
        if not os.path.exists(image_path):
            print(f"Erreur: Image {image_path} non trouvée")
            return False
        
        # Créer un fichier temporaire avec le texte
        with tempfile.NamedTemporaryFile(mode='w', suffix='.txt', delete=False) as f:
            f.write(text)
            temp_file = f.name
        
        try:
            # Commande Steghide
            cmd = ['steghide', 'embed', '-cf', image_path, '-ef', temp_file, '-sf', output_path]
            if password:
                cmd.extend(['-p', password])
            
            result = subprocess.run(cmd, capture_output=True, text=True)
            
            if result.returncode != 0:
                print(f"Erreur Steghide: {result.stderr}")
                return False
            
            print(f"✓ Image stéganographiée créée: {output_path}")
            return True
        
        finally:
            os.unlink(temp_file)
    
    @staticmethod
    def extract_with_steghide(image_path, output_path, password=""):
        """Extrait le texte caché avec Steghide"""
        if not os.path.exists(image_path):
            print(f"Erreur: Image {image_path} non trouvée")
            return False
        
        try:
            cmd = ['steghide', 'extract', '-sf', image_path, '-xf', output_path]
            if password:
                cmd.extend(['-p', password])
            
            result = subprocess.run(cmd, capture_output=True, text=True)
            
            if result.returncode != 0:
                print(f"Erreur extraction: {result.stderr}")
                return False
            
            print(f"✓ Contenu extrait dans: {output_path}")
            return True
        
        finally:
            pass
    
    @staticmethod
    def encode_lsb(image_path, text, output_path):
        """Encode le texte avec LSB (Less Significant Bit) - PIL"""
        try:
            img = Image.open(image_path).convert('RGB')
            pixels = img.load()
            width, height = img.size
            
            # Convertir le texte en binaire
            text_bytes = text.encode('utf-8')
            text_binary = ''.join(format(byte, '08b') for byte in text_bytes)
            text_binary += '1111111111111110'  # Délimiteur de fin
            
            if len(text_binary) > width * height:
                print("Erreur: Le texte est trop long pour l'image")
                return False
            
            idx = 0
            for y in range(height):
                for x in range(width):
                    if idx < len(text_binary):
                        r, g, b = pixels[x, y][:3]
                        bit = int(text_binary[idx])
                        
                        # Changer le LSB du canal rouge
                        r = (r & 0xFE) | bit
                        pixels[x, y] = (r, g, b)
                        
                        idx += 1
                    else:
                        img.save(output_path)
                        print(f"✓ Image LSB créée: {output_path}")
                        return True
            
            img.save(output_path)
            print(f"✓ Image LSB créée: {output_path}")
            return True
        
        except Exception as e:
            print(f"Erreur LSB: {e}")
            return False
    
    @staticmethod
    def decode_lsb(image_path):
        """Décode le texte caché avec LSB"""
        try:
            img = Image.open(image_path).convert('RGB')
            pixels = img.load()
            width, height = img.size
            
            binary_string = ''
            for y in range(height):
                for x in range(width):
                    r, g, b = pixels[x, y][:3]
                    lsb = r & 1
                    binary_string += str(lsb)
                    
                    # Chercher le délimiteur
                    if len(binary_string) >= 16:
                        if binary_string[-16:] == '1111111111111110':
                            binary_string = binary_string[:-16]
                            # Convertir en texte
                            text = ''
                            for i in range(0, len(binary_string), 8):
                                byte = binary_string[i:i+8]
                                if len(byte) == 8:
                                    text += chr(int(byte, 2))
                            print(f"✓ Texte extrait:\n{text}")
                            return text
            
            print("Erreur: Pas de délimiteur trouvé")
            return None
        
        except Exception as e:
            print(f"Erreur décodage LSB: {e}")
            return None


def main():
    parser = argparse.ArgumentParser(
        description='Stéganographie pour phishing éducatif'
    )
    
    parser.add_argument('--image', help='Image source')
    parser.add_argument('--output', help='Fichier de sortie')
    parser.add_argument('--sender', type=int, help='ID de l\'utilisateur attaquant')
    parser.add_argument('--text', help='Texte à cacher (optionnel)')
    parser.add_argument('--password', default='', help='Mot de passe (Steghide)')
    parser.add_argument('--method', choices=['steghide', 'lsb'], default='steghide', 
                       help='Méthode de stéganographie')
    parser.add_argument('--extract', action='store_true', help='Extraction au lieu d\'encodage')
    parser.add_argument('--info', action='store_true', help='Affiche les informations de l\'image')
    
    args = parser.parse_args()
    
    if args.info and args.image:
        try:
            img = Image.open(args.image)
            print(f"Format: {img.format}")
            print(f"Taille: {img.width}x{img.height}")
            print(f"Mode: {img.mode}")
            print(f"Capacité approximative: {(img.width * img.height) // 8} bytes")
        except Exception as e:
            print(f"Erreur: {e}")
        return
    
    if args.extract:
        if not args.image or not args.output:
            print("--image et --output requis pour l'extraction")
            return
        
        if args.method == 'steghide':
            SteganographyHelper.extract_with_steghide(args.image, args.output, args.password)
        else:
            SteganographyHelper.decode_lsb(args.image)
        return
    
    # Encodage
    if not args.image or not args.output:
        print("Erreur: --image et --output requis")
        print("\nUtilisation:")
        print("  Générer payload (Pop-up):")
        print("    python3 steganography.py --image test.png --output output.png --sender 1")
        print("\n  Extraire le contenu:")
        print("    python3 steganography.py --image output.png --output extracted.txt --extract")
        print("\n  Afficher les infos de l'image:")
        print("    python3 steganography.py --image test.png --info")
        return
    
    # Générer le payload
    if args.sender:
        text = SteganographyHelper.generate_payload(args.sender)
        print(f"Payload généré: {text}")
    elif args.text:
        text = args.text
    else:
        print("Erreur: --sender ou --text requis")
        return
    
    # Encoder
    if args.method == 'steghide':
        print(f"Encodage avec Steghide...")
        SteganographyHelper.encode_with_steghide(args.image, text, args.output, args.password)
    else:
        print(f"Encodage avec LSB...")
        SteganographyHelper.encode_lsb(args.image, text, args.output)


if __name__ == '__main__':
    main()
