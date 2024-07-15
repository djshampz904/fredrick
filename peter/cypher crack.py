# The table as a 2D list
table = [
    ['A', 'G', 'L', 'Q', 'V'],
    ['B', 'H', 'M', 'R', 'W'],
    ['C', 'I', 'N', 'S', 'X'],
    ['D', 'J', 'O', 'T', 'Y'],
    ['E', 'K', 'P', 'U', 'Z'],
    ['F', '', '', '', '']
]

# The mapping
mapping = {'t': 'e', 'q': 't', 'a': 'h', 'n': 'a', 'h': 'i', 'i': 'n',
           'p': 'o', 'r': 'b', 'd': 'r', 'l': 'd', 'j': 's','f': 'w', 'z': 'f', 'c': 'm',
           'm': 'l', 'x': 'u', 'u': 'g', 'g': 'c', 's': 'y', 'w': 'p', 'v': 'k', 'e': 'v'}

# Create a reverse mapping where the keys are the English letters and the values are the cipher letters
reverse_mapping = {v: k for k, v in mapping.items()}

# Replace the letters in the table with the mapping
for i in range(len(table)):
    for j in range(len(table[i])):
        if table[i][j].lower() in reverse_mapping:
            table[i][j] = reverse_mapping[table[i][j].lower()].upper() if table[i][j].isupper() else reverse_mapping[table[i][j].lower()]
        else:
            table[i][j] = ''

# Print the updated table as comma-separated values
for row in table:
    print(','.join(row))

import string

# All possible lowercase letters
all_letters = set(string.ascii_lowercase)

# Letters in the keys and values of the mapping
mapped_keys = set(mapping.keys())
mapped_values = set(mapping.values())

# Letters that have not been matched
unmatched_keys = all_letters - mapped_keys
unmatched_values = all_letters - mapped_values

print("Unmatched keys:", unmatched_keys)
print("Unmatched values:", unmatched_values)