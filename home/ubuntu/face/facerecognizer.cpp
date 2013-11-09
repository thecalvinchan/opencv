#include "opencv2/contrib/contrib.hpp"
#include "opencv2/core/core.hpp"
#include "opencv2/highgui/highgui.hpp"

#include <iostream>
#include <fstream>
#include <sstream>

using namespace cv;
using namespace std;

int main(int argc, const char *argv[]) {
    if (argc < 2) {
        cout << "usage: " << argv[0] << " <classifier.yaml> <image.jpg> "<< endl;
        exit(1);
    }
    //      cv::createEigenFaceRecognizer(10);
    //
    // If you want to create a FaceRecognizer with a
    // confidence threshold (e.g. 123.0), call it with:
    //
    //      cv::createEigenFaceRecognizer(10, 123.0);
    //
    // If you want to use _all_ Eigenfaces and have a threshold,
    // then call the method like this:
    //
    //      cv::createEigenFaceRecognizer(0, 123.0);
    //
    Ptr<FaceRecognizer> model1 = createEigenFaceRecognizer();
    model1->load(string(argv[1]));
    // The following line predicts the label of a given
    // test image:
    Mat img = imread(string(argv[2]), CV_LOAD_IMAGE_GRAYSCALE);
    int predictedLabel = model1->predict(img);
    //
    // To get the confidence of a prediction call the model with:
    //
    //      int predictedLabel = -1;
    //      double confidence = 0.0;
    //      model->predict(testSample, predictedLabel, confidence);
    //
    string result_message = format("Predicted class = %d", predictedLabel);
    cout << result_message << endl;
    return 0;
}
