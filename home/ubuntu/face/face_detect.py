#!/usr/bin/python

# Has dependency on the OpenCV Library
# OpenCV: http://opencv.org/
import cv

import argparse
import json
from PIL import Image 
from time import gmtime, strftime

# Globals

MIN_SIZE = (20, 20)
IMAGE_SCALE = 2
HAAR_SCALE = 1.2
MIN_NEIGHBORS = 2
HAAR_FLAGS = 0
HAAR_TRAINER = "/home/ubuntu/face/haarcascade_frontalface_alt.xml"

class Imag:
  def __init__(self,image_name,cascade):
    try:
      image = cv.LoadImage(image_name, 1)
    except IOError:
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
  def __init__(self,images,trainer,owner):
    try:
      cascade = cv.Load(trainer)
    except TypeError:
      return 
    except:
      return
    else: 
      self.data = {}
      self.owner = str(owner)
      for image_name in images:
        image = Imag(image_name,cascade)
        self.data[image_name]=image.faces
    
  def printDataJSON(self):
    size = 200, 200
    data = [] 
    for key in self.data.keys():
      im = Image.open(key)
      facenum = 0
      for crop in self.data[key]:
        time = strftime("%Y-%m-%d-%H-%M-%S", gmtime())
        im.crop((crop['x'],crop['y'],crop['x']+crop['width'],crop['y']+crop['height']))
        im.resize(size).save('/tmp/resized/'+self.owner+'/'+time+self.owner+str(facenum)+'.jpg')
        data.append('/tmp/resized/'+self.owner+'/'+time+self.owner+str(facenum)+'.jpg')
        facenum = facenum + 1
    print(json.dumps(data))

def main():
  parser = argparse.ArgumentParser(description='Facial detection program built on the OpenCV library.')
  parser.add_argument('files', nargs='*', help='<FILE 1> <FILE 2> <FILE 3>...')
  parser.add_argument('--cascade', dest='cascade', default=HAAR_TRAINER, help='Haar cascade file trained facial detection')
  parser.add_argument('--owner', dest='owner', help='Haar cascade file trained facial detection')
  pargs = parser.parse_args()

  images = batchImag(pargs.files,pargs.cascade,pargs.owner)
  images.printDataJSON()

if __name__ == "__main__":
  main()
