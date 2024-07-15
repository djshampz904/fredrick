from collections import Counter
import string

english_freq = {'e': 12.02, 't': 9.10, 'a': 8.12, 'o': 7.68, 'i': 7.31, 'n': 6.95, 's': 6.28, 'r': 6.02, 'h': 5.92,
                'd': 4.32, 'l': 3.98, 'u': 2.88, 'c': 2.71, 'm': 2.61, 'f': 2.30, 'y': 2.11, 'w': 2.09, 'g': 2.03,
                'p': 1.82, 'b': 1.49, 'v': 1.11, 'k': 0.69, 'x': 0.17, 'q': 0.11, 'j': 0.10, 'z': 0.07}

cipher_text = """qat lnqn tigdswqhpi jqnilndl (ltj) fnj qat zhdjq 
gpcctdghnm-udnlt cpltdi gdswqpudnwahg nmupdhqac fhqa 
pwtims nil zxmms jwtghzhtl hcwmtctiqnqhpi ltqnhmj. 
hq fnj ltetmpwtl zdpc mxghztd nil etds jppi rtgnct n
jqnilndl zpd tigdswqhpi hi rnivhiu nil pqatd ipi-chmhqnds 
nwwmhgnqhpij. hq xjtj qat jnct zthjqtm jqdxgqxdt
fhqa japdqtd 64-rhq lnqn rmpgvj nil n japdqtd 64-rhq vts.
"""


def count_frequency(cryptogram):
    frequency = {}
    for char in cryptogram:
        if char.isalpha():
            if char in frequency:
                frequency[char] += 1
            else:
                frequency[char] = 1
    return frequency


cipher_frequency = count_frequency(cipher_text)
# sort the dictionary by values in descending order
sorted_cipher_frequency = sorted(cipher_frequency.items(), key=lambda x: x[1], reverse=True)
sorted_cipher_frequency_dict = dict(sorted_cipher_frequency)
print(sorted_cipher_frequency_dict)
# replace mapping of the most frequent letter in the cipher text with the most frequent letter in English
# and so on
mapping = {}
for i, char in enumerate(sorted_cipher_frequency_dict):
    mapping[char] = list(english_freq.keys())[i]
print(mapping)

# replace the letters in the cipher text with the mapping
deciphered_text = ''
for char in cipher_text:
    if char.isalpha():
        if char.isupper():
            deciphered_text += mapping[char.lower()].upper()
        else:
            deciphered_text += mapping[char]
    else:
        deciphered_text += char
print(deciphered_text)


