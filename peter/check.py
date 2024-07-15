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