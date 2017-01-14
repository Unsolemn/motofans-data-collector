Run:
```
docker run -it --rm --name my-running-script -v "$PWD":/usr/src/myapp -w /usr/src/myapp php-extractor php utils/get_keys.php
```
Run order:  

tables-from-models.php  
stript-HTML-of-tags.php  
data-from-tables.php  
normalize.php  
unit_separation.php  
unit_separation_metric.php  
prepare_for_hugo.php  
