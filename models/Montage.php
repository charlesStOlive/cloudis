<?php namespace Waka\Cloudis\Models;

use Model;

/**
 * Montage Model
 */
class Montage extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\NestedTree;
    use \October\Rain\Database\Traits\SoftDelete;
    //
    use \Waka\Cloudis\Classes\Traits\CloudiTrait;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'waka_cloudis_montages';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:waka_cloudis_montages',
        'data_source_id' => 'required',
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = ['options'];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
        'src' => 'Waka\Cloudis\Models\CloudiFile',
        'masque' => 'Waka\Cloudis\Models\CloudiFile',
    ];
    public $attachMany = [];

    /**
     * Event
     */
    public function afterUpdate()
    {
        $this->testCloudis();
        if ($this->auto_create) {
            $this->updateCLoudiRelationsFromMontage();
        }
    }
    /**
     * Attributes
     */
    public function getCloudi()
    {
        return $this->src();
    }

    public function filterFields($fields, $context = null)
    {
        $user = \BackendAuth::getUser();
        if (!$user->hasAccess('waka.cloudis.admin.*')) {
            $fields->options->hidden = true;
            $fields->data_source_id->readOnly = true;
            $fields->slug->readOnly = true;
            $fields->use_files->readOnly = true;
        }
    }

    /***
     * LISTs
     */
    public function listDataSource()
    {
        return \Waka\Utils\Classes\DataSourceList::lists();
    }
}
