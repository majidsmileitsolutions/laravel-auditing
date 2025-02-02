<?php

namespace OwenIt\Auditing\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ArticleExcludes extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $table = 'articles';

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'reviewed' => 'bool',
        'config' => 'json',
    ];

    public $auditExclude = ['title'];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'published_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'title',
        'content',
        'published_at',
        'reviewed',
    ];

    public function __construct(array $attributes = [])
    {
        if (class_exists(\Illuminate\Database\Eloquent\Casts\AsArrayObject::class)) {
            $this->casts['config'] = \Illuminate\Database\Eloquent\Casts\AsArrayObject::class;
        }
        parent::__construct($attributes);
    }

    /**
     * Uppercase Title accessor.
     */
    public function getTitleAttribute(string $value): string
    {
        return strtoupper($value);
    }
}
