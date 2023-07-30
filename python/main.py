from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from langchain.chat_models import ChatOpenAI
from langchain.schema import (
    AIMessage,
    HumanMessage,
    SystemMessage
)
from typing import List
from itertools import zip_longest

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=['*'],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

class ChatRequest(BaseModel):
    api_key: str
    user_messages: List[str] 
    bot_messages: List[str]

@app.post("/chat")
async def chat(chat_request: ChatRequest):

    # Initialize ChatOpenAI instance with provided API key
    chatGPT = ChatOpenAI(model_name="gpt-3.5-turbo", verbose=True, openai_api_key=chat_request.api_key, max_tokens=300, temperature=0, top_p=1, frequency_penalty=0.0, presence_penalty=0.0)

    # Create the chat history
    messages = []

    len_user_messages = len(chat_request.user_messages)
    len_bot_messages = len(chat_request.bot_messages)

    for i in range(max(len_user_messages, len_bot_messages)):
        if i < len_bot_messages:
            messages.append(AIMessage(content=chat_request.bot_messages[i]))
        if i < len_user_messages:
            messages.append(HumanMessage(content=chat_request.user_messages[i]))

    # Generate response
    response = chatGPT(messages)

    return {"response": response.content}
