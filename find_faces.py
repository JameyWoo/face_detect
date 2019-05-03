from PIL import Image
import json
import time

def draw4lines(location, filename): # location为元素为[height, left, top, width]（人脸位置）的列表
    img_src = './upload/' + filename
    img = Image.open(img_src)
    line = (255, 0, 0, 0)
    for each in location:
        height, left, top, width = tuple(each)
        # 加粗
        for i in [top, top-1, top - 2, height + top, height + top + 1, height + top + 2]:
            for j in range(left - 2, left + width + 3):
                img.putpixel((j, i), line)
        for i in [left, left - 1, left - 2, left + width, left + width + 1, left + width + 2]:
            for j in range(top, top + height):
                img.putpixel((i, j), line)
    img.save("./upload/result_" + filename)

def get_locations(json_name):
    with open(json_name, 'r') as file:
        txt = file.read()
        # data = json.loads(json.dumps(eval(txt))) # 两种方法解决单引号问题
        data = json.loads(txt.replace('\'', '"'))
        # print(type(data))
    locations = []
    for each in data['faces']:
        height = each['face_rectangle']['height']
        left = each['face_rectangle']['left']
        top = each['face_rectangle']['top']
        width = each['face_rectangle']['width']
        locations.append([height, left, top, width])
    return locations

with open('./currentFile.txt', 'r') as file:
    txt = file.read()

# time.sleep(3)
locations = get_locations('result.json')    
draw4lines(locations, txt)