#!/bin/bash

# Manual Docker Image Push Script
# This script tags and pushes Docker images to GitHub Container Registry

set -e

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Configuration
GITHUB_USER="CarboniDavide"
REPO_NAME="mob_montreaux"
REGISTRY="ghcr.io"

echo -e "${BLUE}================================${NC}"
echo -e "${BLUE}Docker Image Push Script${NC}"
echo -e "${BLUE}================================${NC}"
echo ""

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}❌ Error: Docker is not running${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Docker is running${NC}"

# Login to GitHub Container Registry
echo ""
echo -e "${YELLOW}→ Logging in to GitHub Container Registry...${NC}"
echo "Please enter your GitHub Personal Access Token (PAT)"
echo "Create one at: https://github.com/settings/tokens (with 'write:packages' permission)"
echo ""

docker login ghcr.io -u "$GITHUB_USER"

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Login failed${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Logged in successfully${NC}"

# Get version/tag
echo ""
echo -e "${YELLOW}→ Enter tag version (default: latest):${NC}"
read -r TAG
TAG=${TAG:-latest}

# Build and tag backend image
echo ""
echo -e "${BLUE}================================${NC}"
echo -e "${BLUE}Building Backend Image${NC}"
echo -e "${BLUE}================================${NC}"

docker build -t "$REGISTRY/$GITHUB_USER/$REPO_NAME/backend:$TAG" ./backend

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Backend image built successfully${NC}"
else
    echo -e "${RED}❌ Backend build failed${NC}"
    exit 1
fi

# Build and tag frontend image
echo ""
echo -e "${BLUE}================================${NC}"
echo -e "${BLUE}Building Frontend Image${NC}"
echo -e "${BLUE}================================${NC}"

docker build -t "$REGISTRY/$GITHUB_USER/$REPO_NAME/frontend:$TAG" ./frontend

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Frontend image built successfully${NC}"
else
    echo -e "${RED}❌ Frontend build failed${NC}"
    exit 1
fi

# Push images
echo ""
echo -e "${BLUE}================================${NC}"
echo -e "${BLUE}Pushing Images${NC}"
echo -e "${BLUE}================================${NC}"

echo ""
echo -e "${YELLOW}→ Pushing backend image...${NC}"
docker push "$REGISTRY/$GITHUB_USER/$REPO_NAME/backend:$TAG"

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Backend image pushed successfully${NC}"
else
    echo -e "${RED}❌ Backend push failed${NC}"
    exit 1
fi

echo ""
echo -e "${YELLOW}→ Pushing frontend image...${NC}"
docker push "$REGISTRY/$GITHUB_USER/$REPO_NAME/frontend:$TAG"

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Frontend image pushed successfully${NC}"
else
    echo -e "${RED}❌ Frontend push failed${NC}"
    exit 1
fi

# Summary
echo ""
echo -e "${BLUE}================================${NC}"
echo -e "${GREEN}✓ All Done!${NC}"
echo -e "${BLUE}================================${NC}"
echo ""
echo "Images pushed:"
echo "  • $REGISTRY/$GITHUB_USER/$REPO_NAME/backend:$TAG"
echo "  • $REGISTRY/$GITHUB_USER/$REPO_NAME/frontend:$TAG"
echo ""
echo "View your images at:"
echo "  https://github.com/$GITHUB_USER?tab=packages"
echo ""
echo "Pull commands:"
echo "  docker pull $REGISTRY/$GITHUB_USER/$REPO_NAME/backend:$TAG"
echo "  docker pull $REGISTRY/$GITHUB_USER/$REPO_NAME/frontend:$TAG"
echo ""
