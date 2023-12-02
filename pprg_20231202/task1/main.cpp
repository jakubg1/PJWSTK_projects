#include <iostream>

// Code written as part of PJWSTK lessons
// Task 1: Fast sorting

int table[] = {1, 5, 39, 32, 49, 19, 2, 14, 19, 39, 18, 43, 38, 67, 35, 8, 27, 67, 82, 28, 32, 90, 57, 9, 1, 6, 37, 29, 272, 86, 59, 26, 5, 98, 4, 69};
const int TABLE_SIZE = sizeof(table) / sizeof(table[0]);

void fastSort(int l, int r) {
    // Don't sort if it's the only thing.
    if (l == r) {
        return;
    }

    // Grab something as a reference number.
    int mean = table[r];
    int mid = -1;
    // Iterate through the numbers and compare them to the reference number.
    for (int i = l; i <= r; i++) {
        if (table[i] >= mean) { // devnote: this `if` will be always entered at least once as the `mean` value will be compared to itself; this means that `mid` will never stay at -1
            // If we've got a big number, look from the end for a spot to put this number in.
            // If there is no spot for such a number, the sorting step is complete.
            bool spotFound = false;
            for (int j = r; j > i; j--) {
                if (table[j] <= mean) {
                    int x = table[i];
                    table[i] = table[j];
                    table[j] = x;
                    spotFound = true;
                    break;
                }
            }
            // If there is no spot for such a number, the sorting step is complete.
            mid = i;
            if (!spotFound) {
                break;
            }
        }
    }

    // Some debug code :>
    /*
    std::cout << "Podczas sortowania (l=" << l << " r=" << r << "): ";
    for (int i = 0; i < TABLE_SIZE; i++) {
        if (i == l) {
            std::cout << "[";
        }
        if (i == mid) {
            std::cout << "| ";
        }
        std::cout << table[i] << ", ";
        if (i == r) {
            std::cout << "] ";
        }
    }
    std::cout << "  mid = " << mid << std::endl;
    */

    // Sort both sides.
    fastSort(l, mid - 1);
    fastSort(mid, r);
}

int main() {
    srand(time(NULL));
    for (int i = 0; i < TABLE_SIZE; i++) {
        table[i] = (1 + (rand() % 7) * (rand() % 5)) * (rand() % 50);
    }

    std::cout << "Przed sortowaniem: ";
    for (int i = 0; i < TABLE_SIZE; i++) {
        std::cout << table[i] << ", ";
    }
    fastSort(0, TABLE_SIZE - 1);
    std::cout << std::endl;
    std::cout << "Po sortowaniu: ";
    for (int i = 0; i < TABLE_SIZE; i++) {
        std::cout << table[i] << ", ";
    }

    return 0;
}