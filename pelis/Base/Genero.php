<?php

namespace pelis\Base;

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
use pelis\Genero as ChildGenero;
use pelis\GeneroQuery as ChildGeneroQuery;
use pelis\Pelicula as ChildPelicula;
use pelis\PeliculaGenero as ChildPeliculaGenero;
use pelis\PeliculaGeneroQuery as ChildPeliculaGeneroQuery;
use pelis\PeliculaQuery as ChildPeliculaQuery;
use pelis\Map\GeneroTableMap;
use pelis\Map\PeliculaGeneroTableMap;

/**
 * Base class that represents a row from the 'genero' table.
 *
 *
 *
 * @package    propel.generator.pelis.Base
 */
abstract class Genero implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\pelis\\Map\\GeneroTableMap';


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
     * The value for the nombre field.
     *
     * @var        string
     */
    protected $nombre;

    /**
     * @var        ObjectCollection|ChildPeliculaGenero[] Collection to store aggregation of ChildPeliculaGenero objects.
     */
    protected $collPeliculaGeneros;
    protected $collPeliculaGenerosPartial;

    /**
     * @var        ObjectCollection|ChildPelicula[] Cross Collection to store aggregation of ChildPelicula objects.
     */
    protected $collPeliculas;

    /**
     * @var bool
     */
    protected $collPeliculasPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPelicula[]
     */
    protected $peliculasScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPeliculaGenero[]
     */
    protected $peliculaGenerosScheduledForDeletion = null;

    /**
     * Initializes internal state of pelis\Base\Genero object.
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
     * Compares this with another <code>Genero</code> instance.  If
     * <code>obj</code> is an instance of <code>Genero</code>, delegates to
     * <code>equals(Genero)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Genero The current object, for fluid interface
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
     * Get the [nombre] column value.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\pelis\Genero The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[GeneroTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [nombre] column.
     *
     * @param string $v new value
     * @return $this|\pelis\Genero The current object (for fluent API support)
     */
    public function setNombre($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->nombre !== $v) {
            $this->nombre = $v;
            $this->modifiedColumns[GeneroTableMap::COL_NOMBRE] = true;
        }

        return $this;
    } // setNombre()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : GeneroTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : GeneroTableMap::translateFieldName('Nombre', TableMap::TYPE_PHPNAME, $indexType)];
            $this->nombre = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 2; // 2 = GeneroTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\pelis\\Genero'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(GeneroTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildGeneroQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collPeliculaGeneros = null;

            $this->collPeliculas = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Genero::setDeleted()
     * @see Genero::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(GeneroTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildGeneroQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(GeneroTableMap::DATABASE_NAME);
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
                GeneroTableMap::addInstanceToPool($this);
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

            if ($this->peliculasScheduledForDeletion !== null) {
                if (!$this->peliculasScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->peliculasScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \pelis\PeliculaGeneroQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->peliculasScheduledForDeletion = null;
                }

            }

            if ($this->collPeliculas) {
                foreach ($this->collPeliculas as $pelicula) {
                    if (!$pelicula->isDeleted() && ($pelicula->isNew() || $pelicula->isModified())) {
                        $pelicula->save($con);
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

        $this->modifiedColumns[GeneroTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . GeneroTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(GeneroTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(GeneroTableMap::COL_NOMBRE)) {
            $modifiedColumns[':p' . $index++]  = 'nombre';
        }

        $sql = sprintf(
            'INSERT INTO genero (%s) VALUES (%s)',
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
                    case 'nombre':
                        $stmt->bindValue($identifier, $this->nombre, PDO::PARAM_STR);
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
        $pos = GeneroTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getNombre();
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

        if (isset($alreadyDumpedObjects['Genero'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Genero'][$this->hashCode()] = true;
        $keys = GeneroTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getNombre(),
        );
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
     * @return $this|\pelis\Genero
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = GeneroTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\pelis\Genero
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setNombre($value);
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
        $keys = GeneroTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setNombre($arr[$keys[1]]);
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
     * @return $this|\pelis\Genero The current object, for fluid interface
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
        $criteria = new Criteria(GeneroTableMap::DATABASE_NAME);

        if ($this->isColumnModified(GeneroTableMap::COL_ID)) {
            $criteria->add(GeneroTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(GeneroTableMap::COL_NOMBRE)) {
            $criteria->add(GeneroTableMap::COL_NOMBRE, $this->nombre);
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
        $criteria = ChildGeneroQuery::create();
        $criteria->add(GeneroTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \pelis\Genero (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setNombre($this->getNombre());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getPeliculaGeneros() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPeliculaGenero($relObj->copy($deepCopy));
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
     * @return \pelis\Genero Clone of current object.
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
     * If this ChildGenero is new, it will return
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
                    ->filterByGenero($this)
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
     * @return $this|ChildGenero The current object (for fluent API support)
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
            $peliculaGeneroRemoved->setGenero(null);
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
                ->filterByGenero($this)
                ->count($con);
        }

        return count($this->collPeliculaGeneros);
    }

    /**
     * Method called to associate a ChildPeliculaGenero object to this object
     * through the ChildPeliculaGenero foreign key attribute.
     *
     * @param  ChildPeliculaGenero $l ChildPeliculaGenero
     * @return $this|\pelis\Genero The current object (for fluent API support)
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
        $peliculaGenero->setGenero($this);
    }

    /**
     * @param  ChildPeliculaGenero $peliculaGenero The ChildPeliculaGenero object to remove.
     * @return $this|ChildGenero The current object (for fluent API support)
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
            $peliculaGenero->setGenero(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Genero is new, it will return
     * an empty collection; or if this Genero has previously
     * been saved, it will retrieve related PeliculaGeneros from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Genero.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPeliculaGenero[] List of ChildPeliculaGenero objects
     */
    public function getPeliculaGenerosJoinPelicula(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPeliculaGeneroQuery::create(null, $criteria);
        $query->joinWith('Pelicula', $joinBehavior);

        return $this->getPeliculaGeneros($query, $con);
    }

    /**
     * Clears out the collPeliculas collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPeliculas()
     */
    public function clearPeliculas()
    {
        $this->collPeliculas = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collPeliculas crossRef collection.
     *
     * By default this just sets the collPeliculas collection to an empty collection (like clearPeliculas());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPeliculas()
    {
        $collectionClassName = PeliculaGeneroTableMap::getTableMap()->getCollectionClassName();

        $this->collPeliculas = new $collectionClassName;
        $this->collPeliculasPartial = true;
        $this->collPeliculas->setModel('\pelis\Pelicula');
    }

    /**
     * Checks if the collPeliculas collection is loaded.
     *
     * @return bool
     */
    public function isPeliculasLoaded()
    {
        return null !== $this->collPeliculas;
    }

    /**
     * Gets a collection of ChildPelicula objects related by a many-to-many relationship
     * to the current object by way of the pelicula_genero cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGenero is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildPelicula[] List of ChildPelicula objects
     */
    public function getPeliculas(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPeliculasPartial && !$this->isNew();
        if (null === $this->collPeliculas || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collPeliculas) {
                    $this->initPeliculas();
                }
            } else {

                $query = ChildPeliculaQuery::create(null, $criteria)
                    ->filterByGenero($this);
                $collPeliculas = $query->find($con);
                if (null !== $criteria) {
                    return $collPeliculas;
                }

                if ($partial && $this->collPeliculas) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collPeliculas as $obj) {
                        if (!$collPeliculas->contains($obj)) {
                            $collPeliculas[] = $obj;
                        }
                    }
                }

                $this->collPeliculas = $collPeliculas;
                $this->collPeliculasPartial = false;
            }
        }

        return $this->collPeliculas;
    }

    /**
     * Sets a collection of Pelicula objects related by a many-to-many relationship
     * to the current object by way of the pelicula_genero cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $peliculas A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildGenero The current object (for fluent API support)
     */
    public function setPeliculas(Collection $peliculas, ConnectionInterface $con = null)
    {
        $this->clearPeliculas();
        $currentPeliculas = $this->getPeliculas();

        $peliculasScheduledForDeletion = $currentPeliculas->diff($peliculas);

        foreach ($peliculasScheduledForDeletion as $toDelete) {
            $this->removePelicula($toDelete);
        }

        foreach ($peliculas as $pelicula) {
            if (!$currentPeliculas->contains($pelicula)) {
                $this->doAddPelicula($pelicula);
            }
        }

        $this->collPeliculasPartial = false;
        $this->collPeliculas = $peliculas;

        return $this;
    }

    /**
     * Gets the number of Pelicula objects related by a many-to-many relationship
     * to the current object by way of the pelicula_genero cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Pelicula objects
     */
    public function countPeliculas(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPeliculasPartial && !$this->isNew();
        if (null === $this->collPeliculas || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPeliculas) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getPeliculas());
                }

                $query = ChildPeliculaQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByGenero($this)
                    ->count($con);
            }
        } else {
            return count($this->collPeliculas);
        }
    }

    /**
     * Associate a ChildPelicula to this object
     * through the pelicula_genero cross reference table.
     *
     * @param ChildPelicula $pelicula
     * @return ChildGenero The current object (for fluent API support)
     */
    public function addPelicula(ChildPelicula $pelicula)
    {
        if ($this->collPeliculas === null) {
            $this->initPeliculas();
        }

        if (!$this->getPeliculas()->contains($pelicula)) {
            // only add it if the **same** object is not already associated
            $this->collPeliculas->push($pelicula);
            $this->doAddPelicula($pelicula);
        }

        return $this;
    }

    /**
     *
     * @param ChildPelicula $pelicula
     */
    protected function doAddPelicula(ChildPelicula $pelicula)
    {
        $peliculaGenero = new ChildPeliculaGenero();

        $peliculaGenero->setPelicula($pelicula);

        $peliculaGenero->setGenero($this);

        $this->addPeliculaGenero($peliculaGenero);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pelicula->isGenerosLoaded()) {
            $pelicula->initGeneros();
            $pelicula->getGeneros()->push($this);
        } elseif (!$pelicula->getGeneros()->contains($this)) {
            $pelicula->getGeneros()->push($this);
        }

    }

    /**
     * Remove pelicula of this object
     * through the pelicula_genero cross reference table.
     *
     * @param ChildPelicula $pelicula
     * @return ChildGenero The current object (for fluent API support)
     */
    public function removePelicula(ChildPelicula $pelicula)
    {
        if ($this->getPeliculas()->contains($pelicula)) {
            $peliculaGenero = new ChildPeliculaGenero();
            $peliculaGenero->setPelicula($pelicula);
            if ($pelicula->isGenerosLoaded()) {
                //remove the back reference if available
                $pelicula->getGeneros()->removeObject($this);
            }

            $peliculaGenero->setGenero($this);
            $this->removePeliculaGenero(clone $peliculaGenero);
            $peliculaGenero->clear();

            $this->collPeliculas->remove($this->collPeliculas->search($pelicula));

            if (null === $this->peliculasScheduledForDeletion) {
                $this->peliculasScheduledForDeletion = clone $this->collPeliculas;
                $this->peliculasScheduledForDeletion->clear();
            }

            $this->peliculasScheduledForDeletion->push($pelicula);
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
        $this->nombre = null;
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
            if ($this->collPeliculas) {
                foreach ($this->collPeliculas as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collPeliculaGeneros = null;
        $this->collPeliculas = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(GeneroTableMap::DEFAULT_STRING_FORMAT);
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
