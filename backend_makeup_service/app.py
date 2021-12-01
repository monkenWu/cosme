import os
os.environ["KMP_DUPLICATE_LIB_OK"]="TRUE"
from dotenv import load_dotenv
load_dotenv()

import argparse
from pathlib import Path

from PIL import Image
from psgan import Inference
from fire import Fire
import numpy as np



import faceutils as futils
from psgan import PostProcess
from setup import setup_config, setup_argparser
import uuid
from flask import Flask, request, Response
import json

app = Flask(__name__)

config = setup_config()
# Using the second cpu
inference = Inference(
    config, os.getenv('USING_DEVICE') , "assets/models/G.pth")
postprocess = PostProcess(config)

# 首頁方法
@app.route("/", methods=['get'])
def home():
    return Response(json.dumps({'msg': "on",}), mimetype='application/json')

# 上妝處理方法
# path = /api/v1/makeup
# method = post
# parmetgers = {"userIMG" : "string","referenceIMG" : "string"}
# response = {"fileName" : "string"}
@app.route("/api/v1/makeup", methods=['post'])
def makeup():
    data = request.get_json(force=True)

    source = Image.open(os.getenv('SOURCE_PATH') + data["userIMG"]).convert("RGB")
    reference = Image.open(os.getenv('REFERENCE_PATH') + data["referenceIMG"]).convert("RGB")

    # Transfer the psgan from reference to source.
    image, face = inference.transfer(source, reference, with_face=True)
    source_crop = source.crop(
        (face.left(), face.top(), face.right(), face.bottom()))
    image = postprocess(source_crop, image)
    fileName = str(uuid.uuid4())+".png"
    image.save(os.getenv('SYNTHESIZE_PSTH') + fileName)

    returnData = {
        'fileName': fileName
    }
    
    return Response(json.dumps(returnData), mimetype='application/json')