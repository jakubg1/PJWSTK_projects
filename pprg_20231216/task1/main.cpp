#include <iostream>
#include <cmath>

// Code written as part of PJWSTK lessons
// Task 1: Points and distances

struct Point {
    double x;
    double y;
};

double pointDistance(Point p1, Point p2) {
    return std::sqrt(pow(p1.x - p2.x, 2) + pow(p1.y - p2.y, 2));
}

int main() {
    Point p1 = {5, -1};
    Point p2 = {-4, 2};
    Point p3 = {-0.5, 14};
    Point p4 = {0, 0};

    std::cout << "Odleglosci miedzy punktami:" << std::endl;
    std::cout << "p1->p2: " << pointDistance(p1, p2) << std::endl;
    std::cout << "p1->p3: " << pointDistance(p1, p3) << std::endl;
    std::cout << "p1->p4: " << pointDistance(p1, p4) << std::endl;
    std::cout << "p2->p3: " << pointDistance(p2, p3) << std::endl;
    std::cout << "p2->p4: " << pointDistance(p2, p4) << std::endl;
    std::cout << "p3->p4: " << pointDistance(p3, p4) << std::endl;
    std::cout << "p4->p4: " << pointDistance(p4, p4) << std::endl;
    std::cout << "p4->p1: " << pointDistance(p4, p1) << std::endl;

    return 0;
}