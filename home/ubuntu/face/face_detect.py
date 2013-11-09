#!/usr/bin/python

# Has dependency on the OpenCV Library
# OpenCV: http://opencv.org/
import cv

import argparse
import json

# Globals

MIN_SIZE = (20, 20)
IMAGE_SCALE = 2
HAAR_SCALE = 1.2
MIN_NEIGHBORS = 2
HAAR_FLAGS = 0
HAAR_TRAINER = "haarcascade_frontalface_alt.xml"

class Imag:
  faces = []
  def __init__(self,image_name,cascade):
    try:
      image = cv.LoadImage(image_name, 1)
    except IOError:
      #print("No such file or directory")
      return 
    except:
      return
    else:
      #Allocate Space for grayscale image and tiny image
      #Dramatically reduces computation time in exchange for temporary space
      grayscale = cv.CreateImage((image.width,image.height),8,1)
      img = cv.CreateImage((cv.Round(image.width/IMAGE_SCALE),cv.Round(image.height/IMAGE_SCALE)),8,1)

      cv.CvtColor(image,grayscale,cv.CV_BGR2GRAY)
      cv.Resize(grayscale,img,cv.CV_INTER_LINEAR)
      cv.EqualizeHist(img,img)

      matches = cv.HaarDetectObjects(img,cascade,cv.CreateMemStorage(0),HAAR_SCALE,IMAGE_SCALE,HAAR_FLAGS,MIN_SIZE)
      for ((x,y,width,height),wat) in matches:
        self.faces.append({"x":x,"y":y,"width":width,"height":height})
      self.name=image_name

class batchImag:
  images = []
  data = {}
  def __init__(self,images,trainer):
    try:
      cascade = cv.Load(trainer)
    except TypeError:
      return 
    except:
      return
    else: 
      for image_name in images:
        image = Imag(image_name,cascade)
        self.data[image_name]=image.faces
        self.images.append(image)
  def printDataJSON(self):
    print(json.dumps(self.data))
  # draw() function doesn't really work within the class
  # because of typecasting imperfections between the Python 
  # binding and C++ implementation of OpenCL... 
  # Handling this in Canvas frontend
  def draw(self):
    for image in self.images:
      for face in image.faces:
        pt1 = (int(face['x'] * IMAGE_SCALE), int(face['y'] * IMAGE_SCALE))
        pt2 = (int((face['x'] + face['width']) * IMAGE_SCALE), int((face['y'] + face['height']) * IMAGE_SCALE))
        cv.Rectangle(image, pt1, pt2, cv.RGB(255, 0, 0), 3, 8, 0)
      cv.ShowImage(image.name, image)
    cv.WaitKey(0)
    for image in self.images:
      cv.DestroyWindow(image.name)

def main():
  parser = argparse.ArgumentParser(description='Facial detection program built on the OpenCV library.')
  parser.add_argument('files', nargs='*', help='<FILE 1> <FILE 2> <FILE 3>...')
  parser.add_argument('--cascade', dest='cascade', default=HAAR_TRAINER, help='Haar cascade file trained facial detection')
  pargs = parser.parse_args()

  images = batchImag(pargs.files,pargs.cascade)
  images.printDataJSON()

if __name__ == "__main__":
  main()
