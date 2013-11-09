#!/usr/bin/python

# Has dependency on the OpenCV Library
# OpenCV: http://opencv.org/
import cv

import argparse
import json
from PIL import Image 

# Globals

MIN_SIZE = (20, 20)
IMAGE_SCALE = 2
HAAR_SCALE = 1.2
MIN_NEIGHBORS = 2
HAAR_FLAGS = 0
HAAR_TRAINER = "haarcascade_frontalface_alt.xml"

class Imag:
  def __init__(self,image_name,cascade):
    try:
      image = cv.LoadImage(image_name, 1)
    except IOError:
      #print("No such file or directory")
      return 
    except:
      return
    else:
      self.faces = []
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
  def __init__(self,images,trainer):
    try:
      cascade = cv.Load(trainer)
    except TypeError:
      return 
    except:
      return
    else: 
      self.data = {}
      for image_name in images:
        image = Imag(image_name,cascade)
        self.data[image_name]=image.faces
        #print self.data
    
  def printDataJSON(self):
    size = 200, 200
    data = [] 
    for key in self.data.keys():
      #print key
      im = Image.open(key)
      facenum = 0
      for crop in self.data[key]:
        #print crop
        im.crop((crop['x'],crop['y'],crop['x']+crop['width'],crop['y']+crop['height']))
        im.resize(size).save('thumb'+str(facenum)+'_'+key)
        data.append('thumb'+str(facenum)+'_'+key)
        facenum = facenum + 1
    print(json.dumps(data))

def main():
  parser = argparse.ArgumentParser(description='Facial detection program built on the OpenCV library.')
  parser.add_argument('files', nargs='*', help='<FILE 1> <FILE 2> <FILE 3>...')
  parser.add_argument('--cascade', dest='cascade', default=HAAR_TRAINER, help='Haar cascade file trained facial detection')
  pargs = parser.parse_args()

  images = batchImag(pargs.files,pargs.cascade)
  images.printDataJSON()

if __name__ == "__main__":
  main()
