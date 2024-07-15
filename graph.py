# Define the schedules
S1 = ['r3(X)', 'r3(Z)', 'r1(Y)', 'r1(X)', 'w3(Z)', 'r2(Y)', 'w1(X)', 'r2(X)', 'w2(X)', 'r1(Z)', 'w1(Z)', 'r2(Z)',
      'w2(Y)']
S2 = ['r2(X)', 'r3(Y)', 'r2(Z)', 'w3(Y)', 'r1(X)', 'w2(Z)', 'r1(Y)', 'r3(Z)', 'r1(Z)', 'r2(Y)', 'w2(Y)', 'w3(Z)',
      'w1(X)', 'w1(Z)']


# Define a function to create a serialization graph
# Define a function to create a serialization graph
def create_serialization_graph(schedule):
    graph = {'T1': [], 'T2': [], 'T3': []}
    for i in range(len(schedule)):
        for j in range(i + 1, len(schedule)):
            if schedule[i][1] != schedule[j][1] and schedule[i][3] == schedule[j][3]:
                graph['T' + schedule[i][1]].append('T' + schedule[j][1])
    return graph


# Create the serialization graphs for S1 and S2
graph_S1 = create_serialization_graph(S1)
graph_S2 = create_serialization_graph(S2)

# Print the serialization graphs
print("Serialization graph for S1:")
for key, value in graph_S1.items():
    print(f"{key} -> {value}")

print("\nSerialization graph for S2:")
for key, value in graph_S2.items():
    print(f"{key} -> {value}")
