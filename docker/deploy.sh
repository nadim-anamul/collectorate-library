#!/bin/bash

# Collectorate Library Docker Deployment Script
set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Functions
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Get the project root directory (parent of docker directory)
PROJECT_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$PROJECT_ROOT"

# Check if .env file exists
if [ ! -f .env ]; then
    log_error ".env file not found in project root!"
    log_info "Please create .env file from .env.example"
    exit 1
fi

# Load environment variables
source .env

# Function to deploy development environment
deploy_dev() {
    local compose_file="docker-compose.yml"
    
    # Check for minimal setup
    if [ "$1" = "minimal" ]; then
        compose_file="docker-compose.minimal.yml"
        log_info "Deploying minimal development environment (no phpMyAdmin)..."
    elif [ "$1" = "full" ]; then
        compose_file="docker-compose.full.yml"
        log_info "Deploying full development environment (with phpMyAdmin)..."
    else
        log_info "Deploying development environment..."
    fi
    
    # Build and start containers
    docker-compose -f $compose_file down --remove-orphans
    docker-compose -f $compose_file build --no-cache
    docker-compose -f $compose_file up -d
    
    # Wait for services to be ready
    log_info "Waiting for services to be ready..."
    sleep 10
    
    # Run Laravel setup commands
    log_info "Setting up Laravel application..."
    docker-compose -f $compose_file exec app composer install
    docker-compose -f $compose_file exec app php artisan key:generate
    docker-compose -f $compose_file exec app php artisan migrate --seed
    docker-compose -f $compose_file exec app php artisan storage:link
    
    log_success "Development environment deployed successfully!"
    log_info "Application: http://localhost:8989"
    if [ "$1" != "minimal" ]; then
        log_info "phpMyAdmin: http://localhost:8080"
    fi
}

# Function to deploy production environment
deploy_prod() {
    log_info "Deploying production environment..."
    
    # Check required environment variables
    required_vars=("DB_DATABASE" "DB_USERNAME" "DB_PASSWORD" "DB_ROOT_PASSWORD" "REDIS_PASSWORD" "DOMAIN" "ACME_EMAIL")
    for var in "${required_vars[@]}"; do
        if [ -z "${!var}" ]; then
            log_error "Required environment variable $var is not set!"
            exit 1
        fi
    done
    
    # Build and start containers
    docker-compose -f docker-compose.prod.yml down --remove-orphans
    docker-compose -f docker-compose.prod.yml build --no-cache
    docker-compose -f docker-compose.prod.yml up -d
    
    # Wait for services to be ready
    log_info "Waiting for services to be ready..."
    sleep 15
    
    # Run Laravel setup commands
    log_info "Setting up Laravel application..."
    docker-compose -f docker-compose.prod.yml exec app php artisan key:generate --force
    docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
    docker-compose -f docker-compose.prod.yml exec app php artisan storage:link
    docker-compose -f docker-compose.prod.yml exec app php artisan optimize:production
    
    log_success "Production environment deployed successfully!"
    log_info "Application: https://$DOMAIN"
    log_info "Traefik Dashboard: http://localhost:8080"
}

# Function to show help
show_help() {
    echo "Collectorate Library Docker Deployment Script"
    echo ""
    echo "Usage: $0 [COMMAND]"
    echo ""
    echo "Commands:"
    echo "  dev [minimal|full]  Deploy development environment"
    echo "                       minimal: Laravel + MySQL + Redis (no phpMyAdmin)"
    echo "                       full: Laravel + MySQL + Redis + phpMyAdmin"
    echo "  prod               Deploy production environment"
    echo "  stop               Stop all containers"
    echo "  logs               Show container logs"
    echo "  shell              Access application shell"
    echo "  help               Show this help message"
    echo ""
}

# Main script logic
case "${1:-help}" in
    "dev")
        deploy_dev "$2"
        ;;
    "prod")
        deploy_prod
        ;;
    "stop")
        log_info "Stopping all containers..."
        docker-compose down
        docker-compose -f docker-compose.prod.yml down
        log_success "All containers stopped!"
        ;;
    "logs")
        if [ "$2" = "prod" ]; then
            docker-compose -f docker-compose.prod.yml logs -f
        else
            docker-compose logs -f
        fi
        ;;
    "shell")
        if [ "$2" = "prod" ]; then
            docker-compose -f docker-compose.prod.yml exec app bash
        else
            docker-compose exec app bash
        fi
        ;;
    "help"|*)
        show_help
        ;;
esac
