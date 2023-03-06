## Setup:
    1. Run 'composer install'
    2. Copy .env.sample contents to .env in the root directory and setup your database configuration. (use msyql)
    3. Run 'php/bin console doctrine:database:create'
    4. Run 'php/bin console doctrine:database:migrate'
    5. Run 'php/bin console cache:clear'
    6. Run 'symfony server:start' to start the app locally

## Usage
    You can use the classifier by accessing the following two URLs:
            
    {local_url}/api/classifier/learn 
    input data:
    ```php
    
    {
        documents: ["...", "..."],
        category: "test category"
    }
    
    ```

    expected output:
    ```php
    
    {
        "message":"Succesfully added {X} documents. {Y} documents were invalid."
    }
    
    ```

    {local_url}/api/classifier/classify
    input data:
    ```php
    
    {
        documents: ["...", "..."]
    }
    
    ```

    expected output:
    ```php"
    {
        message": [
            {
                "document": {document representation},
                "probabilities": {
                    {probabilities , key=>value pair of probbabilities, sorted DESC}
                },
                "belongsTo": {category name of the most probbable category}
            },
            { ... }
        ]
    }
    ```

    OR

    by using the BayesClassifier class inside your program, the learn method accepts a Category name and a document text

    ```php
        //include classifier
        use App\Classifier\BayesClassifier;

        $classifier = new BayesClassifier();
    
        $classifier->learn("lorem ipsum", "...");
        $classifier->learn("bacon ipsum", "...");
        $classifier->learn("office ipsum", "...");

        $classifier->classify("...");
    ```