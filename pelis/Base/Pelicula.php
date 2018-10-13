<?php

namespace pelis\Base;

use \DateTime;
use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use pelis\Actor as ChildActor;
use pelis\ActorQuery as ChildActorQuery;
use pelis\Genero as ChildGenero;
use pelis\GeneroQuery as ChildGeneroQuery;
use pelis\Pelicula as ChildPelicula;
use pelis\PeliculaActor as ChildPeliculaActor;
use pelis\PeliculaActorQuery as ChildPeliculaActorQuery;
use pelis\PeliculaGenero as ChildPeliculaGenero;
use pelis\PeliculaGeneroQuery as ChildPeliculaGeneroQuery;
use pelis\PeliculaProductor as ChildPeliculaProductor;
use pelis\PeliculaProductorQuery as ChildPeliculaProductorQuery;
use pelis\PeliculaQuery as ChildPeliculaQuery;
use pelis\Productor as ChildProductor;
use pelis\ProductorQuery as ChildProductorQuery;
use pelis\Map\PeliculaActorTableMap;
use pelis\Map\PeliculaGeneroTableMap;
use pelis\Map\PeliculaProductorTableMap;
use pelis\Map\PeliculaTableMap;

/**
 * Base class that represents a row from the 'pelicula' table.
 *
 *
 *
 * @package    propel.generator.pelis.Base
 */
abstract class Pelicula implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\pelis\\Map\\PeliculaTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the titulo field.
     *
     * @var        string
     */
    protected $titulo;

    /**
     * The value for the ano field.
     *
     * @var        DateTime
     */
    protected $ano;

    /**
     * The value for the sinopsis field.
     *
     * @var        string
     */
    protected $sinopsis;

    /**
     * The value for the trailer field.
     *
     * @var        string
     */
    protected $trailer;

    /**
     * @var        ObjectCollection|ChildPeliculaGenero[] Collection to store aggregation of ChildPeliculaGenero objects.
     */
    protected $collPeliculaGeneros;
    protected $collPeliculaGenerosPartial;

    /**
     * @var        ObjectCollection|ChildPeliculaProductor[] Collection to store aggregation of ChildPeliculaProductor objects.
     */
    protected $collPeliculaProductors;
    protected $collPeliculaProductorsPartial;

    /**
     * @var        ObjectCollection|ChildPeliculaActor[] Collection to store aggregation of ChildPeliculaActor objects.
     */
    protected $collPeliculaActors;
    protected $collPeliculaActorsPartial;

    /**
     * @var        ObjectCollection|ChildGenero[] Cross Collection to store aggregation of ChildGenero objects.
     */
    protected $collGeneros;

    /**
     * @var bool
     */
    protected $collGenerosPartial;

    /**
     * @var        ObjectCollection|ChildProductor[] Cross Collection to store aggregation of ChildProductor objects.
     */
    protected $collProductors;

    /**
     * @var bool
     */
    protected $collProductorsPartial;

    /**
     * @var        ObjectCollection|ChildActor[] Cross Collection to store aggregation of ChildActor objects.
     */
    protected $collActors;

    /**
     * @var bool
     */
    protected $collActorsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGenero[]
     */
    protected $generosScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildProductor[]
     */
    protected $productorsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildActor[]
     */
    protected $actorsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPeliculaGenero[]
     */
    protected $peliculaGenerosScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPeliculaProductor[]
     */
    protected $peliculaProductorsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPeliculaActor[]
     */
    protected $peliculaActorsScheduledForDeletion = null;

    /**
     * Initializes internal state of pelis\Base\Pelicula object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Pelicula</code> instance.  If
     * <code>obj</code> is an instance of <code>Pelicula</code>, delegates to
     * <code>equals(Pelicula)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Pelicula The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [titulo] column value.
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Get the [optionally formatted] temporal [ano] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getAno($format = NULL)
    {
        if ($format === null) {
            return $this->ano;
        } else {
            return $this->ano instanceof \DateTimeInterface ? $this->ano->format($format) : null;
        }
    }

    /**
     * Get the [sinopsis] column value.
     *
     * @return string
     */
    public function getSinopsis()
    {
        return $this->sinopsis;
    }

    /**
     * Get the [trailer] column value.
     *
     * @return string
     */
    public function getTrailer()
    {
        return $this->trailer;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\pelis\Pelicula The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[PeliculaTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [titulo] column.
     *
     * @param string $v new value
     * @return $this|\pelis\Pelicula The current object (for fluent API support)
     */
    public function setTitulo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->titulo !== $v) {
            $this->titulo = $v;
            $this->modifiedColumns[PeliculaTableMap::COL_TITULO] = true;
        }

        return $this;
    } // setTitulo()

    /**
     * Sets the value of [ano] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\pelis\Pelicula The current object (for fluent API support)
     */
    public function setAno($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->ano !== null || $dt !== null) {
            if ($this->ano === null || $dt === null || $dt->format("Y-m-d") !== $this->ano->format("Y-m-d")) {
                $this->ano = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PeliculaTableMap::COL_ANO] = true;
            }
        } // if either are not null

        return $this;
    } // setAno()

    /**
     * Set the value of [sinopsis] column.
     *
     * @param string $v new value
     * @return $this|\pelis\Pelicula The current object (for fluent API support)
     */
    public function setSinopsis($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sinopsis !== $v) {
            $this->sinopsis = $v;
            $this->modifiedColumns[PeliculaTableMap::COL_SINOPSIS] = true;
        }

        return $this;
    } // setSinopsis()

    /**
     * Set the value of [trailer] column.
     *
     * @param string $v new value
     * @return $this|\pelis\Pelicula The current object (for fluent API support)
     */
    public function setTrailer($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->trailer !== $v) {
            $this->trailer = $v;
            $this->modifiedColumns[PeliculaTableMap::COL_TRAILER] = true;
        }

        return $this;
    } // setTrailer()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PeliculaTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PeliculaTableMap::translateFieldName('Titulo', TableMap::TYPE_PHPNAME, $indexType)];
            $this->titulo = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PeliculaTableMap::translateFieldName('Ano', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->ano = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PeliculaTableMap::translateFieldName('Sinopsis', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sinopsis = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PeliculaTableMap::translateFieldName('Trailer', TableMap::TYPE_PHPNAME, $indexType)];
            $this->trailer = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = PeliculaTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\pelis\\Pelicula'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PeliculaTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPeliculaQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collPeliculaGeneros = null;

            $this->collPeliculaProductors = null;

            $this->collPeliculaActors = null;

            $this->collGeneros = null;
            $this->collProductors = null;
            $this->collActors = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Pelicula::setDeleted()
     * @see Pelicula::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PeliculaTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPeliculaQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PeliculaTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                PeliculaTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->generosScheduledForDeletion !== null) {
                if (!$this->generosScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->generosScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \pelis\PeliculaGeneroQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->generosScheduledForDeletion = null;
                }

            }

            if ($this->collGeneros) {
                foreach ($this->collGeneros as $genero) {
                    if (!$genero->isDeleted() && ($genero->isNew() || $genero->isModified())) {
                        $genero->save($con);
                    }
                }
            }


            if ($this->productorsScheduledForDeletion !== null) {
                if (!$this->productorsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->productorsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \pelis\PeliculaProductorQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->productorsScheduledForDeletion = null;
                }

            }

            if ($this->collProductors) {
                foreach ($this->collProductors as $productor) {
                    if (!$productor->isDeleted() && ($productor->isNew() || $productor->isModified())) {
                        $productor->save($con);
                    }
                }
            }


            if ($this->actorsScheduledForDeletion !== null) {
                if (!$this->actorsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->actorsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \pelis\PeliculaActorQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->actorsScheduledForDeletion = null;
                }

            }

            if ($this->collActors) {
                foreach ($this->collActors as $actor) {
                    if (!$actor->isDeleted() && ($actor->isNew() || $actor->isModified())) {
                        $actor->save($con);
                    }
                }
            }


            if ($this->peliculaGenerosScheduledForDeletion !== null) {
                if (!$this->peliculaGenerosScheduledForDeletion->isEmpty()) {
                    \pelis\PeliculaGeneroQuery::create()
                        ->filterByPrimaryKeys($this->peliculaGenerosScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->peliculaGenerosScheduledForDeletion = null;
                }
            }

            if ($this->collPeliculaGeneros !== null) {
                foreach ($this->collPeliculaGeneros as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->peliculaProductorsScheduledForDeletion !== null) {
                if (!$this->peliculaProductorsScheduledForDeletion->isEmpty()) {
                    \pelis\PeliculaProductorQuery::create()
                        ->filterByPrimaryKeys($this->peliculaProductorsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->peliculaProductorsScheduledForDeletion = null;
                }
            }

            if ($this->collPeliculaProductors !== null) {
                foreach ($this->collPeliculaProductors as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->peliculaActorsScheduledForDeletion !== null) {
                if (!$this->peliculaActorsScheduledForDeletion->isEmpty()) {
                    \pelis\PeliculaActorQuery::create()
                        ->filterByPrimaryKeys($this->peliculaActorsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->peliculaActorsScheduledForDeletion = null;
                }
            }

            if ($this->collPeliculaActors !== null) {
                foreach ($this->collPeliculaActors as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[PeliculaTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PeliculaTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PeliculaTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(PeliculaTableMap::COL_TITULO)) {
            $modifiedColumns[':p' . $index++]  = 'titulo';
        }
        if ($this->isColumnModified(PeliculaTableMap::COL_ANO)) {
            $modifiedColumns[':p' . $index++]  = 'ano';
        }
        if ($this->isColumnModified(PeliculaTableMap::COL_SINOPSIS)) {
            $modifiedColumns[':p' . $index++]  = 'sinopsis';
        }
        if ($this->isColumnModified(PeliculaTableMap::COL_TRAILER)) {
            $modifiedColumns[':p' . $index++]  = 'trailer';
        }

        $sql = sprintf(
            'INSERT INTO pelicula (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'titulo':
                        $stmt->bindValue($identifier, $this->titulo, PDO::PARAM_STR);
                        break;
                    case 'ano':
                        $stmt->bindValue($identifier, $this->ano ? $this->ano->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'sinopsis':
                        $stmt->bindValue($identifier, $this->sinopsis, PDO::PARAM_STR);
                        break;
                    case 'trailer':
                        $stmt->bindValue($identifier, $this->trailer, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PeliculaTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getTitulo();
                break;
            case 2:
                return $this->getAno();
                break;
            case 3:
                return $this->getSinopsis();
                break;
            case 4:
                return $this->getTrailer();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Pelicula'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Pelicula'][$this->hashCode()] = true;
        $keys = PeliculaTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitulo(),
            $keys[2] => $this->getAno(),
            $keys[3] => $this->getSinopsis(),
            $keys[4] => $this->getTrailer(),
        );
        if ($result[$keys[2]] instanceof \DateTimeInterface) {
            $result[$keys[2]] = $result[$keys[2]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collPeliculaGeneros) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'peliculaGeneros';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'pelicula_generos';
                        break;
                    default:
                        $key = 'PeliculaGeneros';
                }

                $result[$key] = $this->collPeliculaGeneros->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPeliculaProductors) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'peliculaProductors';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'pelicula_productors';
                        break;
                    default:
                        $key = 'PeliculaProductors';
                }

                $result[$key] = $this->collPeliculaProductors->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPeliculaActors) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'peliculaActors';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'pelicula_actors';
                        break;
                    default:
                        $key = 'PeliculaActors';
                }

                $result[$key] = $this->collPeliculaActors->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\pelis\Pelicula
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PeliculaTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\pelis\Pelicula
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setTitulo($value);
                break;
            case 2:
                $this->setAno($value);
                break;
            case 3:
                $this->setSinopsis($value);
                break;
            case 4:
                $this->setTrailer($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = PeliculaTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTitulo($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setAno($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setSinopsis($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setTrailer($arr[$keys[4]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\pelis\Pelicula The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PeliculaTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PeliculaTableMap::COL_ID)) {
            $criteria->add(PeliculaTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(PeliculaTableMap::COL_TITULO)) {
            $criteria->add(PeliculaTableMap::COL_TITULO, $this->titulo);
        }
        if ($this->isColumnModified(PeliculaTableMap::COL_ANO)) {
            $criteria->add(PeliculaTableMap::COL_ANO, $this->ano);
        }
        if ($this->isColumnModified(PeliculaTableMap::COL_SINOPSIS)) {
            $criteria->add(PeliculaTableMap::COL_SINOPSIS, $this->sinopsis);
        }
        if ($this->isColumnModified(PeliculaTableMap::COL_TRAILER)) {
            $criteria->add(PeliculaTableMap::COL_TRAILER, $this->trailer);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildPeliculaQuery::create();
        $criteria->add(PeliculaTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \pelis\Pelicula (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitulo($this->getTitulo());
        $copyObj->setAno($this->getAno());
        $copyObj->setSinopsis($this->getSinopsis());
        $copyObj->setTrailer($this->getTrailer());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getPeliculaGeneros() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPeliculaGenero($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPeliculaProductors() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPeliculaProductor($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPeliculaActors() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPeliculaActor($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \pelis\Pelicula Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('PeliculaGenero' == $relationName) {
            $this->initPeliculaGeneros();
            return;
        }
        if ('PeliculaProductor' == $relationName) {
            $this->initPeliculaProductors();
            return;
        }
        if ('PeliculaActor' == $relationName) {
            $this->initPeliculaActors();
            return;
        }
    }

    /**
     * Clears out the collPeliculaGeneros collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPeliculaGeneros()
     */
    public function clearPeliculaGeneros()
    {
        $this->collPeliculaGeneros = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPeliculaGeneros collection loaded partially.
     */
    public function resetPartialPeliculaGeneros($v = true)
    {
        $this->collPeliculaGenerosPartial = $v;
    }

    /**
     * Initializes the collPeliculaGeneros collection.
     *
     * By default this just sets the collPeliculaGeneros collection to an empty array (like clearcollPeliculaGeneros());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPeliculaGeneros($overrideExisting = true)
    {
        if (null !== $this->collPeliculaGeneros && !$overrideExisting) {
            return;
        }

        $collectionClassName = PeliculaGeneroTableMap::getTableMap()->getCollectionClassName();

        $this->collPeliculaGeneros = new $collectionClassName;
        $this->collPeliculaGeneros->setModel('\pelis\PeliculaGenero');
    }

    /**
     * Gets an array of ChildPeliculaGenero objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPelicula is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPeliculaGenero[] List of ChildPeliculaGenero objects
     * @throws PropelException
     */
    public function getPeliculaGeneros(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPeliculaGenerosPartial && !$this->isNew();
        if (null === $this->collPeliculaGeneros || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPeliculaGeneros) {
                // return empty collection
                $this->initPeliculaGeneros();
            } else {
                $collPeliculaGeneros = ChildPeliculaGeneroQuery::create(null, $criteria)
                    ->filterByPelicula($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPeliculaGenerosPartial && count($collPeliculaGeneros)) {
                        $this->initPeliculaGeneros(false);

                        foreach ($collPeliculaGeneros as $obj) {
                            if (false == $this->collPeliculaGeneros->contains($obj)) {
                                $this->collPeliculaGeneros->append($obj);
                            }
                        }

                        $this->collPeliculaGenerosPartial = true;
                    }

                    return $collPeliculaGeneros;
                }

                if ($partial && $this->collPeliculaGeneros) {
                    foreach ($this->collPeliculaGeneros as $obj) {
                        if ($obj->isNew()) {
                            $collPeliculaGeneros[] = $obj;
                        }
                    }
                }

                $this->collPeliculaGeneros = $collPeliculaGeneros;
                $this->collPeliculaGenerosPartial = false;
            }
        }

        return $this->collPeliculaGeneros;
    }

    /**
     * Sets a collection of ChildPeliculaGenero objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $peliculaGeneros A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPelicula The current object (for fluent API support)
     */
    public function setPeliculaGeneros(Collection $peliculaGeneros, ConnectionInterface $con = null)
    {
        /** @var ChildPeliculaGenero[] $peliculaGenerosToDelete */
        $peliculaGenerosToDelete = $this->getPeliculaGeneros(new Criteria(), $con)->diff($peliculaGeneros);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->peliculaGenerosScheduledForDeletion = clone $peliculaGenerosToDelete;

        foreach ($peliculaGenerosToDelete as $peliculaGeneroRemoved) {
            $peliculaGeneroRemoved->setPelicula(null);
        }

        $this->collPeliculaGeneros = null;
        foreach ($peliculaGeneros as $peliculaGenero) {
            $this->addPeliculaGenero($peliculaGenero);
        }

        $this->collPeliculaGeneros = $peliculaGeneros;
        $this->collPeliculaGenerosPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PeliculaGenero objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PeliculaGenero objects.
     * @throws PropelException
     */
    public function countPeliculaGeneros(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPeliculaGenerosPartial && !$this->isNew();
        if (null === $this->collPeliculaGeneros || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPeliculaGeneros) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPeliculaGeneros());
            }

            $query = ChildPeliculaGeneroQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPelicula($this)
                ->count($con);
        }

        return count($this->collPeliculaGeneros);
    }

    /**
     * Method called to associate a ChildPeliculaGenero object to this object
     * through the ChildPeliculaGenero foreign key attribute.
     *
     * @param  ChildPeliculaGenero $l ChildPeliculaGenero
     * @return $this|\pelis\Pelicula The current object (for fluent API support)
     */
    public function addPeliculaGenero(ChildPeliculaGenero $l)
    {
        if ($this->collPeliculaGeneros === null) {
            $this->initPeliculaGeneros();
            $this->collPeliculaGenerosPartial = true;
        }

        if (!$this->collPeliculaGeneros->contains($l)) {
            $this->doAddPeliculaGenero($l);

            if ($this->peliculaGenerosScheduledForDeletion and $this->peliculaGenerosScheduledForDeletion->contains($l)) {
                $this->peliculaGenerosScheduledForDeletion->remove($this->peliculaGenerosScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPeliculaGenero $peliculaGenero The ChildPeliculaGenero object to add.
     */
    protected function doAddPeliculaGenero(ChildPeliculaGenero $peliculaGenero)
    {
        $this->collPeliculaGeneros[]= $peliculaGenero;
        $peliculaGenero->setPelicula($this);
    }

    /**
     * @param  ChildPeliculaGenero $peliculaGenero The ChildPeliculaGenero object to remove.
     * @return $this|ChildPelicula The current object (for fluent API support)
     */
    public function removePeliculaGenero(ChildPeliculaGenero $peliculaGenero)
    {
        if ($this->getPeliculaGeneros()->contains($peliculaGenero)) {
            $pos = $this->collPeliculaGeneros->search($peliculaGenero);
            $this->collPeliculaGeneros->remove($pos);
            if (null === $this->peliculaGenerosScheduledForDeletion) {
                $this->peliculaGenerosScheduledForDeletion = clone $this->collPeliculaGeneros;
                $this->peliculaGenerosScheduledForDeletion->clear();
            }
            $this->peliculaGenerosScheduledForDeletion[]= clone $peliculaGenero;
            $peliculaGenero->setPelicula(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Pelicula is new, it will return
     * an empty collection; or if this Pelicula has previously
     * been saved, it will retrieve related PeliculaGeneros from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Pelicula.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPeliculaGenero[] List of ChildPeliculaGenero objects
     */
    public function getPeliculaGenerosJoinGenero(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPeliculaGeneroQuery::create(null, $criteria);
        $query->joinWith('Genero', $joinBehavior);

        return $this->getPeliculaGeneros($query, $con);
    }

    /**
     * Clears out the collPeliculaProductors collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPeliculaProductors()
     */
    public function clearPeliculaProductors()
    {
        $this->collPeliculaProductors = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPeliculaProductors collection loaded partially.
     */
    public function resetPartialPeliculaProductors($v = true)
    {
        $this->collPeliculaProductorsPartial = $v;
    }

    /**
     * Initializes the collPeliculaProductors collection.
     *
     * By default this just sets the collPeliculaProductors collection to an empty array (like clearcollPeliculaProductors());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPeliculaProductors($overrideExisting = true)
    {
        if (null !== $this->collPeliculaProductors && !$overrideExisting) {
            return;
        }

        $collectionClassName = PeliculaProductorTableMap::getTableMap()->getCollectionClassName();

        $this->collPeliculaProductors = new $collectionClassName;
        $this->collPeliculaProductors->setModel('\pelis\PeliculaProductor');
    }

    /**
     * Gets an array of ChildPeliculaProductor objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPelicula is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPeliculaProductor[] List of ChildPeliculaProductor objects
     * @throws PropelException
     */
    public function getPeliculaProductors(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPeliculaProductorsPartial && !$this->isNew();
        if (null === $this->collPeliculaProductors || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPeliculaProductors) {
                // return empty collection
                $this->initPeliculaProductors();
            } else {
                $collPeliculaProductors = ChildPeliculaProductorQuery::create(null, $criteria)
                    ->filterByPelicula($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPeliculaProductorsPartial && count($collPeliculaProductors)) {
                        $this->initPeliculaProductors(false);

                        foreach ($collPeliculaProductors as $obj) {
                            if (false == $this->collPeliculaProductors->contains($obj)) {
                                $this->collPeliculaProductors->append($obj);
                            }
                        }

                        $this->collPeliculaProductorsPartial = true;
                    }

                    return $collPeliculaProductors;
                }

                if ($partial && $this->collPeliculaProductors) {
                    foreach ($this->collPeliculaProductors as $obj) {
                        if ($obj->isNew()) {
                            $collPeliculaProductors[] = $obj;
                        }
                    }
                }

                $this->collPeliculaProductors = $collPeliculaProductors;
                $this->collPeliculaProductorsPartial = false;
            }
        }

        return $this->collPeliculaProductors;
    }

    /**
     * Sets a collection of ChildPeliculaProductor objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $peliculaProductors A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPelicula The current object (for fluent API support)
     */
    public function setPeliculaProductors(Collection $peliculaProductors, ConnectionInterface $con = null)
    {
        /** @var ChildPeliculaProductor[] $peliculaProductorsToDelete */
        $peliculaProductorsToDelete = $this->getPeliculaProductors(new Criteria(), $con)->diff($peliculaProductors);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->peliculaProductorsScheduledForDeletion = clone $peliculaProductorsToDelete;

        foreach ($peliculaProductorsToDelete as $peliculaProductorRemoved) {
            $peliculaProductorRemoved->setPelicula(null);
        }

        $this->collPeliculaProductors = null;
        foreach ($peliculaProductors as $peliculaProductor) {
            $this->addPeliculaProductor($peliculaProductor);
        }

        $this->collPeliculaProductors = $peliculaProductors;
        $this->collPeliculaProductorsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PeliculaProductor objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PeliculaProductor objects.
     * @throws PropelException
     */
    public function countPeliculaProductors(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPeliculaProductorsPartial && !$this->isNew();
        if (null === $this->collPeliculaProductors || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPeliculaProductors) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPeliculaProductors());
            }

            $query = ChildPeliculaProductorQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPelicula($this)
                ->count($con);
        }

        return count($this->collPeliculaProductors);
    }

    /**
     * Method called to associate a ChildPeliculaProductor object to this object
     * through the ChildPeliculaProductor foreign key attribute.
     *
     * @param  ChildPeliculaProductor $l ChildPeliculaProductor
     * @return $this|\pelis\Pelicula The current object (for fluent API support)
     */
    public function addPeliculaProductor(ChildPeliculaProductor $l)
    {
        if ($this->collPeliculaProductors === null) {
            $this->initPeliculaProductors();
            $this->collPeliculaProductorsPartial = true;
        }

        if (!$this->collPeliculaProductors->contains($l)) {
            $this->doAddPeliculaProductor($l);

            if ($this->peliculaProductorsScheduledForDeletion and $this->peliculaProductorsScheduledForDeletion->contains($l)) {
                $this->peliculaProductorsScheduledForDeletion->remove($this->peliculaProductorsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPeliculaProductor $peliculaProductor The ChildPeliculaProductor object to add.
     */
    protected function doAddPeliculaProductor(ChildPeliculaProductor $peliculaProductor)
    {
        $this->collPeliculaProductors[]= $peliculaProductor;
        $peliculaProductor->setPelicula($this);
    }

    /**
     * @param  ChildPeliculaProductor $peliculaProductor The ChildPeliculaProductor object to remove.
     * @return $this|ChildPelicula The current object (for fluent API support)
     */
    public function removePeliculaProductor(ChildPeliculaProductor $peliculaProductor)
    {
        if ($this->getPeliculaProductors()->contains($peliculaProductor)) {
            $pos = $this->collPeliculaProductors->search($peliculaProductor);
            $this->collPeliculaProductors->remove($pos);
            if (null === $this->peliculaProductorsScheduledForDeletion) {
                $this->peliculaProductorsScheduledForDeletion = clone $this->collPeliculaProductors;
                $this->peliculaProductorsScheduledForDeletion->clear();
            }
            $this->peliculaProductorsScheduledForDeletion[]= clone $peliculaProductor;
            $peliculaProductor->setPelicula(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Pelicula is new, it will return
     * an empty collection; or if this Pelicula has previously
     * been saved, it will retrieve related PeliculaProductors from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Pelicula.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPeliculaProductor[] List of ChildPeliculaProductor objects
     */
    public function getPeliculaProductorsJoinProductor(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPeliculaProductorQuery::create(null, $criteria);
        $query->joinWith('Productor', $joinBehavior);

        return $this->getPeliculaProductors($query, $con);
    }

    /**
     * Clears out the collPeliculaActors collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPeliculaActors()
     */
    public function clearPeliculaActors()
    {
        $this->collPeliculaActors = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPeliculaActors collection loaded partially.
     */
    public function resetPartialPeliculaActors($v = true)
    {
        $this->collPeliculaActorsPartial = $v;
    }

    /**
     * Initializes the collPeliculaActors collection.
     *
     * By default this just sets the collPeliculaActors collection to an empty array (like clearcollPeliculaActors());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPeliculaActors($overrideExisting = true)
    {
        if (null !== $this->collPeliculaActors && !$overrideExisting) {
            return;
        }

        $collectionClassName = PeliculaActorTableMap::getTableMap()->getCollectionClassName();

        $this->collPeliculaActors = new $collectionClassName;
        $this->collPeliculaActors->setModel('\pelis\PeliculaActor');
    }

    /**
     * Gets an array of ChildPeliculaActor objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPelicula is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPeliculaActor[] List of ChildPeliculaActor objects
     * @throws PropelException
     */
    public function getPeliculaActors(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPeliculaActorsPartial && !$this->isNew();
        if (null === $this->collPeliculaActors || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPeliculaActors) {
                // return empty collection
                $this->initPeliculaActors();
            } else {
                $collPeliculaActors = ChildPeliculaActorQuery::create(null, $criteria)
                    ->filterByPelicula($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPeliculaActorsPartial && count($collPeliculaActors)) {
                        $this->initPeliculaActors(false);

                        foreach ($collPeliculaActors as $obj) {
                            if (false == $this->collPeliculaActors->contains($obj)) {
                                $this->collPeliculaActors->append($obj);
                            }
                        }

                        $this->collPeliculaActorsPartial = true;
                    }

                    return $collPeliculaActors;
                }

                if ($partial && $this->collPeliculaActors) {
                    foreach ($this->collPeliculaActors as $obj) {
                        if ($obj->isNew()) {
                            $collPeliculaActors[] = $obj;
                        }
                    }
                }

                $this->collPeliculaActors = $collPeliculaActors;
                $this->collPeliculaActorsPartial = false;
            }
        }

        return $this->collPeliculaActors;
    }

    /**
     * Sets a collection of ChildPeliculaActor objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $peliculaActors A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPelicula The current object (for fluent API support)
     */
    public function setPeliculaActors(Collection $peliculaActors, ConnectionInterface $con = null)
    {
        /** @var ChildPeliculaActor[] $peliculaActorsToDelete */
        $peliculaActorsToDelete = $this->getPeliculaActors(new Criteria(), $con)->diff($peliculaActors);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->peliculaActorsScheduledForDeletion = clone $peliculaActorsToDelete;

        foreach ($peliculaActorsToDelete as $peliculaActorRemoved) {
            $peliculaActorRemoved->setPelicula(null);
        }

        $this->collPeliculaActors = null;
        foreach ($peliculaActors as $peliculaActor) {
            $this->addPeliculaActor($peliculaActor);
        }

        $this->collPeliculaActors = $peliculaActors;
        $this->collPeliculaActorsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PeliculaActor objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PeliculaActor objects.
     * @throws PropelException
     */
    public function countPeliculaActors(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPeliculaActorsPartial && !$this->isNew();
        if (null === $this->collPeliculaActors || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPeliculaActors) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPeliculaActors());
            }

            $query = ChildPeliculaActorQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPelicula($this)
                ->count($con);
        }

        return count($this->collPeliculaActors);
    }

    /**
     * Method called to associate a ChildPeliculaActor object to this object
     * through the ChildPeliculaActor foreign key attribute.
     *
     * @param  ChildPeliculaActor $l ChildPeliculaActor
     * @return $this|\pelis\Pelicula The current object (for fluent API support)
     */
    public function addPeliculaActor(ChildPeliculaActor $l)
    {
        if ($this->collPeliculaActors === null) {
            $this->initPeliculaActors();
            $this->collPeliculaActorsPartial = true;
        }

        if (!$this->collPeliculaActors->contains($l)) {
            $this->doAddPeliculaActor($l);

            if ($this->peliculaActorsScheduledForDeletion and $this->peliculaActorsScheduledForDeletion->contains($l)) {
                $this->peliculaActorsScheduledForDeletion->remove($this->peliculaActorsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPeliculaActor $peliculaActor The ChildPeliculaActor object to add.
     */
    protected function doAddPeliculaActor(ChildPeliculaActor $peliculaActor)
    {
        $this->collPeliculaActors[]= $peliculaActor;
        $peliculaActor->setPelicula($this);
    }

    /**
     * @param  ChildPeliculaActor $peliculaActor The ChildPeliculaActor object to remove.
     * @return $this|ChildPelicula The current object (for fluent API support)
     */
    public function removePeliculaActor(ChildPeliculaActor $peliculaActor)
    {
        if ($this->getPeliculaActors()->contains($peliculaActor)) {
            $pos = $this->collPeliculaActors->search($peliculaActor);
            $this->collPeliculaActors->remove($pos);
            if (null === $this->peliculaActorsScheduledForDeletion) {
                $this->peliculaActorsScheduledForDeletion = clone $this->collPeliculaActors;
                $this->peliculaActorsScheduledForDeletion->clear();
            }
            $this->peliculaActorsScheduledForDeletion[]= clone $peliculaActor;
            $peliculaActor->setPelicula(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Pelicula is new, it will return
     * an empty collection; or if this Pelicula has previously
     * been saved, it will retrieve related PeliculaActors from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Pelicula.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPeliculaActor[] List of ChildPeliculaActor objects
     */
    public function getPeliculaActorsJoinActor(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPeliculaActorQuery::create(null, $criteria);
        $query->joinWith('Actor', $joinBehavior);

        return $this->getPeliculaActors($query, $con);
    }

    /**
     * Clears out the collGeneros collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGeneros()
     */
    public function clearGeneros()
    {
        $this->collGeneros = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collGeneros crossRef collection.
     *
     * By default this just sets the collGeneros collection to an empty collection (like clearGeneros());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initGeneros()
    {
        $collectionClassName = PeliculaGeneroTableMap::getTableMap()->getCollectionClassName();

        $this->collGeneros = new $collectionClassName;
        $this->collGenerosPartial = true;
        $this->collGeneros->setModel('\pelis\Genero');
    }

    /**
     * Checks if the collGeneros collection is loaded.
     *
     * @return bool
     */
    public function isGenerosLoaded()
    {
        return null !== $this->collGeneros;
    }

    /**
     * Gets a collection of ChildGenero objects related by a many-to-many relationship
     * to the current object by way of the pelicula_genero cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPelicula is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildGenero[] List of ChildGenero objects
     */
    public function getGeneros(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGenerosPartial && !$this->isNew();
        if (null === $this->collGeneros || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collGeneros) {
                    $this->initGeneros();
                }
            } else {

                $query = ChildGeneroQuery::create(null, $criteria)
                    ->filterByPelicula($this);
                $collGeneros = $query->find($con);
                if (null !== $criteria) {
                    return $collGeneros;
                }

                if ($partial && $this->collGeneros) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collGeneros as $obj) {
                        if (!$collGeneros->contains($obj)) {
                            $collGeneros[] = $obj;
                        }
                    }
                }

                $this->collGeneros = $collGeneros;
                $this->collGenerosPartial = false;
            }
        }

        return $this->collGeneros;
    }

    /**
     * Sets a collection of Genero objects related by a many-to-many relationship
     * to the current object by way of the pelicula_genero cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $generos A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildPelicula The current object (for fluent API support)
     */
    public function setGeneros(Collection $generos, ConnectionInterface $con = null)
    {
        $this->clearGeneros();
        $currentGeneros = $this->getGeneros();

        $generosScheduledForDeletion = $currentGeneros->diff($generos);

        foreach ($generosScheduledForDeletion as $toDelete) {
            $this->removeGenero($toDelete);
        }

        foreach ($generos as $genero) {
            if (!$currentGeneros->contains($genero)) {
                $this->doAddGenero($genero);
            }
        }

        $this->collGenerosPartial = false;
        $this->collGeneros = $generos;

        return $this;
    }

    /**
     * Gets the number of Genero objects related by a many-to-many relationship
     * to the current object by way of the pelicula_genero cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Genero objects
     */
    public function countGeneros(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGenerosPartial && !$this->isNew();
        if (null === $this->collGeneros || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGeneros) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getGeneros());
                }

                $query = ChildGeneroQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPelicula($this)
                    ->count($con);
            }
        } else {
            return count($this->collGeneros);
        }
    }

    /**
     * Associate a ChildGenero to this object
     * through the pelicula_genero cross reference table.
     *
     * @param ChildGenero $genero
     * @return ChildPelicula The current object (for fluent API support)
     */
    public function addGenero(ChildGenero $genero)
    {
        if ($this->collGeneros === null) {
            $this->initGeneros();
        }

        if (!$this->getGeneros()->contains($genero)) {
            // only add it if the **same** object is not already associated
            $this->collGeneros->push($genero);
            $this->doAddGenero($genero);
        }

        return $this;
    }

    /**
     *
     * @param ChildGenero $genero
     */
    protected function doAddGenero(ChildGenero $genero)
    {
        $peliculaGenero = new ChildPeliculaGenero();

        $peliculaGenero->setGenero($genero);

        $peliculaGenero->setPelicula($this);

        $this->addPeliculaGenero($peliculaGenero);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$genero->isPeliculasLoaded()) {
            $genero->initPeliculas();
            $genero->getPeliculas()->push($this);
        } elseif (!$genero->getPeliculas()->contains($this)) {
            $genero->getPeliculas()->push($this);
        }

    }

    /**
     * Remove genero of this object
     * through the pelicula_genero cross reference table.
     *
     * @param ChildGenero $genero
     * @return ChildPelicula The current object (for fluent API support)
     */
    public function removeGenero(ChildGenero $genero)
    {
        if ($this->getGeneros()->contains($genero)) {
            $peliculaGenero = new ChildPeliculaGenero();
            $peliculaGenero->setGenero($genero);
            if ($genero->isPeliculasLoaded()) {
                //remove the back reference if available
                $genero->getPeliculas()->removeObject($this);
            }

            $peliculaGenero->setPelicula($this);
            $this->removePeliculaGenero(clone $peliculaGenero);
            $peliculaGenero->clear();

            $this->collGeneros->remove($this->collGeneros->search($genero));

            if (null === $this->generosScheduledForDeletion) {
                $this->generosScheduledForDeletion = clone $this->collGeneros;
                $this->generosScheduledForDeletion->clear();
            }

            $this->generosScheduledForDeletion->push($genero);
        }


        return $this;
    }

    /**
     * Clears out the collProductors collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addProductors()
     */
    public function clearProductors()
    {
        $this->collProductors = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collProductors crossRef collection.
     *
     * By default this just sets the collProductors collection to an empty collection (like clearProductors());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initProductors()
    {
        $collectionClassName = PeliculaProductorTableMap::getTableMap()->getCollectionClassName();

        $this->collProductors = new $collectionClassName;
        $this->collProductorsPartial = true;
        $this->collProductors->setModel('\pelis\Productor');
    }

    /**
     * Checks if the collProductors collection is loaded.
     *
     * @return bool
     */
    public function isProductorsLoaded()
    {
        return null !== $this->collProductors;
    }

    /**
     * Gets a collection of ChildProductor objects related by a many-to-many relationship
     * to the current object by way of the pelicula_productor cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPelicula is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildProductor[] List of ChildProductor objects
     */
    public function getProductors(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collProductorsPartial && !$this->isNew();
        if (null === $this->collProductors || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collProductors) {
                    $this->initProductors();
                }
            } else {

                $query = ChildProductorQuery::create(null, $criteria)
                    ->filterByPelicula($this);
                $collProductors = $query->find($con);
                if (null !== $criteria) {
                    return $collProductors;
                }

                if ($partial && $this->collProductors) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collProductors as $obj) {
                        if (!$collProductors->contains($obj)) {
                            $collProductors[] = $obj;
                        }
                    }
                }

                $this->collProductors = $collProductors;
                $this->collProductorsPartial = false;
            }
        }

        return $this->collProductors;
    }

    /**
     * Sets a collection of Productor objects related by a many-to-many relationship
     * to the current object by way of the pelicula_productor cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $productors A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildPelicula The current object (for fluent API support)
     */
    public function setProductors(Collection $productors, ConnectionInterface $con = null)
    {
        $this->clearProductors();
        $currentProductors = $this->getProductors();

        $productorsScheduledForDeletion = $currentProductors->diff($productors);

        foreach ($productorsScheduledForDeletion as $toDelete) {
            $this->removeProductor($toDelete);
        }

        foreach ($productors as $productor) {
            if (!$currentProductors->contains($productor)) {
                $this->doAddProductor($productor);
            }
        }

        $this->collProductorsPartial = false;
        $this->collProductors = $productors;

        return $this;
    }

    /**
     * Gets the number of Productor objects related by a many-to-many relationship
     * to the current object by way of the pelicula_productor cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Productor objects
     */
    public function countProductors(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collProductorsPartial && !$this->isNew();
        if (null === $this->collProductors || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProductors) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getProductors());
                }

                $query = ChildProductorQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPelicula($this)
                    ->count($con);
            }
        } else {
            return count($this->collProductors);
        }
    }

    /**
     * Associate a ChildProductor to this object
     * through the pelicula_productor cross reference table.
     *
     * @param ChildProductor $productor
     * @return ChildPelicula The current object (for fluent API support)
     */
    public function addProductor(ChildProductor $productor)
    {
        if ($this->collProductors === null) {
            $this->initProductors();
        }

        if (!$this->getProductors()->contains($productor)) {
            // only add it if the **same** object is not already associated
            $this->collProductors->push($productor);
            $this->doAddProductor($productor);
        }

        return $this;
    }

    /**
     *
     * @param ChildProductor $productor
     */
    protected function doAddProductor(ChildProductor $productor)
    {
        $peliculaProductor = new ChildPeliculaProductor();

        $peliculaProductor->setProductor($productor);

        $peliculaProductor->setPelicula($this);

        $this->addPeliculaProductor($peliculaProductor);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$productor->isPeliculasLoaded()) {
            $productor->initPeliculas();
            $productor->getPeliculas()->push($this);
        } elseif (!$productor->getPeliculas()->contains($this)) {
            $productor->getPeliculas()->push($this);
        }

    }

    /**
     * Remove productor of this object
     * through the pelicula_productor cross reference table.
     *
     * @param ChildProductor $productor
     * @return ChildPelicula The current object (for fluent API support)
     */
    public function removeProductor(ChildProductor $productor)
    {
        if ($this->getProductors()->contains($productor)) {
            $peliculaProductor = new ChildPeliculaProductor();
            $peliculaProductor->setProductor($productor);
            if ($productor->isPeliculasLoaded()) {
                //remove the back reference if available
                $productor->getPeliculas()->removeObject($this);
            }

            $peliculaProductor->setPelicula($this);
            $this->removePeliculaProductor(clone $peliculaProductor);
            $peliculaProductor->clear();

            $this->collProductors->remove($this->collProductors->search($productor));

            if (null === $this->productorsScheduledForDeletion) {
                $this->productorsScheduledForDeletion = clone $this->collProductors;
                $this->productorsScheduledForDeletion->clear();
            }

            $this->productorsScheduledForDeletion->push($productor);
        }


        return $this;
    }

    /**
     * Clears out the collActors collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addActors()
     */
    public function clearActors()
    {
        $this->collActors = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collActors crossRef collection.
     *
     * By default this just sets the collActors collection to an empty collection (like clearActors());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initActors()
    {
        $collectionClassName = PeliculaActorTableMap::getTableMap()->getCollectionClassName();

        $this->collActors = new $collectionClassName;
        $this->collActorsPartial = true;
        $this->collActors->setModel('\pelis\Actor');
    }

    /**
     * Checks if the collActors collection is loaded.
     *
     * @return bool
     */
    public function isActorsLoaded()
    {
        return null !== $this->collActors;
    }

    /**
     * Gets a collection of ChildActor objects related by a many-to-many relationship
     * to the current object by way of the pelicula_actor cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPelicula is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildActor[] List of ChildActor objects
     */
    public function getActors(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collActorsPartial && !$this->isNew();
        if (null === $this->collActors || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collActors) {
                    $this->initActors();
                }
            } else {

                $query = ChildActorQuery::create(null, $criteria)
                    ->filterByPelicula($this);
                $collActors = $query->find($con);
                if (null !== $criteria) {
                    return $collActors;
                }

                if ($partial && $this->collActors) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collActors as $obj) {
                        if (!$collActors->contains($obj)) {
                            $collActors[] = $obj;
                        }
                    }
                }

                $this->collActors = $collActors;
                $this->collActorsPartial = false;
            }
        }

        return $this->collActors;
    }

    /**
     * Sets a collection of Actor objects related by a many-to-many relationship
     * to the current object by way of the pelicula_actor cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $actors A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildPelicula The current object (for fluent API support)
     */
    public function setActors(Collection $actors, ConnectionInterface $con = null)
    {
        $this->clearActors();
        $currentActors = $this->getActors();

        $actorsScheduledForDeletion = $currentActors->diff($actors);

        foreach ($actorsScheduledForDeletion as $toDelete) {
            $this->removeActor($toDelete);
        }

        foreach ($actors as $actor) {
            if (!$currentActors->contains($actor)) {
                $this->doAddActor($actor);
            }
        }

        $this->collActorsPartial = false;
        $this->collActors = $actors;

        return $this;
    }

    /**
     * Gets the number of Actor objects related by a many-to-many relationship
     * to the current object by way of the pelicula_actor cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Actor objects
     */
    public function countActors(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collActorsPartial && !$this->isNew();
        if (null === $this->collActors || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collActors) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getActors());
                }

                $query = ChildActorQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPelicula($this)
                    ->count($con);
            }
        } else {
            return count($this->collActors);
        }
    }

    /**
     * Associate a ChildActor to this object
     * through the pelicula_actor cross reference table.
     *
     * @param ChildActor $actor
     * @return ChildPelicula The current object (for fluent API support)
     */
    public function addActor(ChildActor $actor)
    {
        if ($this->collActors === null) {
            $this->initActors();
        }

        if (!$this->getActors()->contains($actor)) {
            // only add it if the **same** object is not already associated
            $this->collActors->push($actor);
            $this->doAddActor($actor);
        }

        return $this;
    }

    /**
     *
     * @param ChildActor $actor
     */
    protected function doAddActor(ChildActor $actor)
    {
        $peliculaActor = new ChildPeliculaActor();

        $peliculaActor->setActor($actor);

        $peliculaActor->setPelicula($this);

        $this->addPeliculaActor($peliculaActor);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$actor->isPeliculasLoaded()) {
            $actor->initPeliculas();
            $actor->getPeliculas()->push($this);
        } elseif (!$actor->getPeliculas()->contains($this)) {
            $actor->getPeliculas()->push($this);
        }

    }

    /**
     * Remove actor of this object
     * through the pelicula_actor cross reference table.
     *
     * @param ChildActor $actor
     * @return ChildPelicula The current object (for fluent API support)
     */
    public function removeActor(ChildActor $actor)
    {
        if ($this->getActors()->contains($actor)) {
            $peliculaActor = new ChildPeliculaActor();
            $peliculaActor->setActor($actor);
            if ($actor->isPeliculasLoaded()) {
                //remove the back reference if available
                $actor->getPeliculas()->removeObject($this);
            }

            $peliculaActor->setPelicula($this);
            $this->removePeliculaActor(clone $peliculaActor);
            $peliculaActor->clear();

            $this->collActors->remove($this->collActors->search($actor));

            if (null === $this->actorsScheduledForDeletion) {
                $this->actorsScheduledForDeletion = clone $this->collActors;
                $this->actorsScheduledForDeletion->clear();
            }

            $this->actorsScheduledForDeletion->push($actor);
        }


        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->titulo = null;
        $this->ano = null;
        $this->sinopsis = null;
        $this->trailer = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collPeliculaGeneros) {
                foreach ($this->collPeliculaGeneros as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPeliculaProductors) {
                foreach ($this->collPeliculaProductors as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPeliculaActors) {
                foreach ($this->collPeliculaActors as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGeneros) {
                foreach ($this->collGeneros as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProductors) {
                foreach ($this->collProductors as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collActors) {
                foreach ($this->collActors as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collPeliculaGeneros = null;
        $this->collPeliculaProductors = null;
        $this->collPeliculaActors = null;
        $this->collGeneros = null;
        $this->collProductors = null;
        $this->collActors = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PeliculaTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
