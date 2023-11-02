import openai
import os
import time
import sys


arg1 = sys.argv[1]
arg2 = sys.argv[2]
openai.api_key = arg1
response=openai.Model.delete((arg2)
print(response)
