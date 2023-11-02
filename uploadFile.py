import openai
import os
import time
import sys


arg1 = sys.argv[1]
arg2 = sys.argv[2]
arg3 = sys.argv[3]

openai.api_key = arg1

response = openai.File.create(
  file=open(arg3, "rb"),
  purpose='fine-tune'
)
file_id = response['id']
print(file_id)
#time.sleep(2)
response2 = openai.FineTuningJob.create(training_file=file_id, model=arg2)
job_id = response2['id']
print(job_id)
