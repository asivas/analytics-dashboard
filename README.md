# Analytics Dashboard
The "CRUD-Resouce" Analytics Dashboard controller, to be used as part of the 
Asivas solution for https://gitlab.com/asivas/larvue-abm/ABM, to simple manage CRUD of resources, 
for backend usage, (expecting to use the asivas vue ABM frontend).

##Requirements


## Instalation / Configuration

You can install this package using composer

```bash
comopser require asivas/analytics-dashboard
```

Once you have required the package the configuration requires this 2 steps:

1. Create your Analitycs Facade class in app\Facades

   This class should have a method for every analytics the app could graph or analyze.
   Each method will end up calling ths getData method of an Specific-Analytic class wich extends Asivas\Analytics\Analytics
   ```php
   <?php
   namespace App\Facades;
   use App\Analytics\SomeMetricsIndicator;
   class Analytics
   {            
         public function someMetricsIndicator($from,$to,$params = null)
         {
           return SomeMetricsIndicator::getData($from,$to,$params);
         }
        // TODO: create the methods similiar to someMetricsIndicator with your criteria
   }
   ```
2. Register the Facade in app\Providers\AppServiceProvider register method

   ```php
   use App\Facades\Analytics;
   class AppServiceProvider extends ServiceProvider
    {
    /**
      * Register any application services.
      * 
      * @return void
      */
     public function register()
     {
        //
        $this->app->bind('Analytics', function($app) {
           return new Analytics();
        });
     }
   ...
   ```
3. Add Analytics alias pointing to Asivas\Analytics\AnalyticsFacade class in the 'aliases' key of config/app.php
    ```php
   'aliases' => [
   ...
     'Analytics' => \Asivas\Analytics\AnalyticsFacade::class
   ...
    ```
5. For every analysis the app should graph or show in the dashboard

   Create a Specific-Analytics class in App\Analytics extending Asivas\Analytics\Analytics
    implementing the addJoins, addGroupBys and addWheres methods
   ```php
   <?php
    namespace App\Analytics;
    
    use App\Models\Report;
    use Asivas\Analytics\Analytics;
    use Illuminate\Database\Eloquent\Builder;
    
    class SomeMetricsIndicator extends Analytics
    {
        protected $mainModel = SomeModel::class;
    
        protected function addJoins(Builder $q)
        {
            return $q->selectRaw('COUNT(some_related_model.created_at) as somemodels, \'assigned\' as status')
                ->join('some_related_models','somemodels.id', '=','some_related_models.somemodel_id')
                ->join('other_related_models','somemodels.id', '=','other_related_models.somemodel_id')
                ->join('related_to_others','other_related_models.related_to_other_id', '=','related_to_others.id')
                ->join('users','related_to_others.user_id', '=','users.id');
        }
    
        protected function addGroupBys(Builder $q)
        {
            return $q->groupBy('status');
        }
    
        protected function addWheres(Builder $q, $from, $to)
        {
            return $q->whereHas('assignations',function ($q)
                        use($from,$to){
                            $q->whereNull('rejected_date')
                                ->where('users.id',request('user'));
                        });
        }
    }
   ```



