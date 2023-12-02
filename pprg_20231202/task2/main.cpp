#include <iostream>

// Code written as part of PJWSTK lessons
// Task 2: Matrix multiplication

// For clarity, a simple example from Wikipedia has been used. Feel free to tweak the hardcoded numbers below.
const int SIZE_A = 2; // num of rows in the result
const int SIZE_B = 3;
const int SIZE_C = 2; // num of columns in the result

int matrix1[SIZE_A][SIZE_B] = {
    {1, 0, 2},
    {-1, 3, 1}
};
int matrix2[SIZE_B][SIZE_C] = {
    {3, 1},
    {2, 1},
    {1, 0}
};
int result[SIZE_A][SIZE_C];

void matrixMul() {
    for (int i = 0; i < SIZE_A; i++) {
        for (int j = 0; j < SIZE_C; j++) {
            // For each cell, this code will be executed.
            int value = 0;
            for (int k = 0; k < SIZE_B; k++) {
                value += matrix1[i][k] * matrix2[k][j];
            }
            result[i][j] = value;
        }
    }
}

int main() {
    matrixMul();
    std::cout << "Wynik:" << std::endl;
    for (int i = 0; i < SIZE_A; i++) {
        for (int j = 0; j < SIZE_C; j++) {
            std::cout << result[i][j] << " ";
        }
        std::cout << std::endl;
    }

    return 0;
}