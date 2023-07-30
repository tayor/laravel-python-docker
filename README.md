# Chatbot Project

This is a chatbot project built using the TALL (Tailwind CSS, Alpine.js, Laravel, Livewire) stack for the frontend and Python/FastAPI for the chatbot backend. It is dockerized to run the frontend and backend in separate containers.

## Overview

- The frontend is built with Laravel, Livewire, Tailwind CSS and Alpine.js. It provides a chat interface for users to interact with the chatbot.

- The backend uses FastAPI (Python web framework) to provide a REST API for the chatbot. It handles sending user messages to the AI model and returning responses.

- Docker Compose is used to run the frontend and backend in separate containers, with a MariaDB container for storage.

## Frontend

The Laravel frontend provides a chat interface using Livewire for real-time updates.

Main components:

- `resources/views/livewire/chat-component.blade.php` - The main chat component UI

- `app/Http/Livewire/ChatComponent.php` - The Livewire component logic

- Calls FastAPI endpoint to get bot responses

- Saves chat history to database

- `routes/web.php` - Routes to serve homepage

- `resources/views/welcome.blade.php` - Homepage layout wrapped in Livewire component

## Backend 

The FastAPI backend handles chatbot requests from the frontend. 

Main files:

- `main.py` - FastAPI app and endpoint for chat requests

- Gets previous messages and returns response from ChatGPT

- Configure API key, parameters etc.

## Docker

Docker Compose runs containers for the frontend, backend and database.

- `docker-compose.yml` - Docker Compose file to run everything

- Laravel frontend container

- FastAPI backend container

- MariaDB database container

Volumes mount source code into containers.

## Run Locally

Prerequisites: Docker and Docker Compose installed

```
git clone <repo>
cd <repo>
docker-compose up
```

Laravel app will be available on http://localhost:8000

FastAPI app on http://localhost:8001
