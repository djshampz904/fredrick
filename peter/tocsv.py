# The cipher text
cipher_text = """qat lnqn tigdswqhpi jqnilndl (ltj) fnj qat zhdjq
gpcctdghnm-udnlt cpltdi gdswqpudnwahg nmupdhqac fhqa
pwtims nil zxmms jwtghzhtl hcwmtctiqnqhpi ltqnhmj.
hq fnj ltetmpwtl zdpc mxghztd nil etds jppi rtgnct n
jqnilndl zpd tigdswqhpi hi rnivhiu nil pqatd ipi-chmhqnds
nwwmhgnqhpij. hq xjtj qat jnct zthjqtm jqdxgqxdt
fhqa japdqtd 64-rhq lnqn rmpgvj nil n japdqtd 64-rhq vts."""

# The mapping you've solved
mapping = {'t': 'e', 'q': 't', 'a': 'h', 'n': 'a', 'h': 'i', 'i': 'n',
           'p': 'o', 'r': 'b', 'd': 'r', 'l': 'd', 'j': 's','f': 'w', 'z': 'f', 'c': 'm',
           'm': 'l', 'x': 'u', 'u': 'g', 'g': 'c', 's': 'y', 'w': 'p', 'v': 'k', 'e': 'v'}

# Replace the letters in the cipher text with the mapping
deciphered_text = ''
for char in cipher_text:
    if char.isalpha():
        if char in mapping:
            deciphered_text += mapping[char]
        else:
            deciphered_text += char
    else:
        deciphered_text += char

print(deciphered_text)