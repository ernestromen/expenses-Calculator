# Use the official Node.js image as the base image
FROM node:14

# Set the working directory to the frontend app
WORKDIR /app

# Copy the frontend files to the container
COPY . /app/

# Install frontend dependencies
RUN npm install

# Build the frontend app
RUN npm run build

# Expose the port on which the Vue.js development server runs (default is 8080)
EXPOSE 8080

# Start the Vue.js development server when the container starts
CMD ["npm", "run", "serve"]
