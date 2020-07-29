# Shopping cart API  
  
### Requirements  
* Docker  
* Docker compose  
  
### Setup  
    docker pull composer
  	
    cd php/src   
      
    docker run --rm -ti -v $PWD:/app -w /app composer install --ignore-platform-reqs  
    
    sudo chmod -R 777 var  
    
    # Back to root path of project  
    
    cd ../../  
    
    docker-compose up -d --build   
      
    curl -S http://127.0.0.1:8080/system/ready  

### API ENDPOINTS  
  
>POST /seller  

	{
		"id" : "cdd7954e-502e-403e-af6f-888e1bdd6450",
		"name" : "seller name"
	}

>DELETE /seller/{id}
  
>POST /product-seller  

	{
		"id" : "cdd7954e-502e-403e-af6f-888e1bdd6450",
		"product_id" : "cdd7954e-502e-403e-af6f-888e1bdd6450",
		"seller_id" : "cdd7954e-502e-403e-af6f-888e1bdd6450",
		"price" : 100
	}

>DELETE /product-seller/{id}

>DELETE /cart/{id}

>POST /cart/{id}/product-seller/{productSellerId}

>DELETE /cart/{id}/product-seller/{productSellerId}

>PATCH /cart/{id}/product-seller/{productSellerId}/units/{units}

>GET /cart/{id}/amount

>PATCH /cart/{id}/confirm


### Tests
  
	# From path php/src     
	docker run --rm -ti -v $PWD:/app -w /app cart_php php vendor/bin/phpunit -c phpunit.xml.dist

### TODO:
* Fix id validation in order to avoid duplicated.
* Request validations.
* Settings for environments.
* Think about REST endpoints methods used.
* Replace file repositories
