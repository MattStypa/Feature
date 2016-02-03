# Feature

Feature is a rampup and AB testing library inspired by Etsy\Feature. It is __NOT__ a direct replacement, but it can do everything Etsy's library can and more.

## API
The main method is:
```
$feature->getVariant($name);
```
This will return a selected variant. It may also return NULL if the feature does not exist or if none of the variants were selected (features with incomplete coverage).

Before you can check for variants you need to define a few methods that will allow the Feature library to retrieve your configuration on-demand. This is done by injecting an implementation of the Connector interface into the Feature instance.

The Connector interface defines two methods:
```
getContext();
```
This is used to retrieve a context in which the variant will be selected. A variant will be selected for each context individually. Most of the time this will be set to a unique user id or session id. You can, for example, set this to reflect an article id. In this case, all users will see the same result but it will change between different articles.

```
getEntity($name);
```
This method allows the Feature instance to retrieve an instance of Entity class that will be used to select a variant.

## Entity
To define a feature, your implementation of the getEntity($name) method needs to return an Entity instance. Once you create an Entity object, add variants to it using the following method:
```
$entity->addVariant($name, $odds)
```
This will add a specified variant to the feature. The odds can range between 0 and 100 and don't need to be whole numbers.

## Migrating from Etsy\Feature
#### How do I check if a feature is enabled?
Etsy's library separates the concept of a feature and variant; which forces you to wrap each call to the variant() method in a conditional.
```
if (Feature::isEnabled('my_feature')) {
    switch (Feature::variant('my_feature')) {
        case 'foo':
            // do stuff appropriate for the foo variant
            break;
        case 'bar':
            // do stuff appropriate for the bar variant
            break;
    }
}
```
We eliminated the isEnabled($name) method completely and use the getVariant($name) method to determine if a feature is enabled:
```
switch $feature->getVariant($name) {
    case 'foo':
        // do stuff appropriate for the foo variant
        break;
    case 'bar':
        // do stuff appropriate for the bar variant
        break;
    case null:
        // feature is not enabled
        break;
}
```
Or:
```
if ($feature->getVariant($name)) {
    // Feature is enabled as long as variant evaluates to true
}
```

#### How do I define a feature that is on 50% of the time and has no variants?
This is done by defining a single variant with the odds set to 50:
```
$entity->addVariant(true, 50);
```
Now you can simply check if the selected variant is true:
```
if ($feature->getVariant($name)) {
    // Feature is on
}
```
#### How do I define a feature that applies to certain users or user groups?
This is done during the configuration of the feature:
```
if (isAdmin()) {
    $entity->addVariant(true, 100);
} else {
    $entity->addVariant(true, 20);
}
```
In the above example, the feature will be always on for admins and for 20% of visitors.
