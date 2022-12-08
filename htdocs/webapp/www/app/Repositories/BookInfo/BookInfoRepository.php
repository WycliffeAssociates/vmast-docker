<?php


namespace App\Repositories\BookInfo;


use App\Models\ORM\BookInfo;

class BookInfoRepository implements IBookInfoRepository
{
    protected $bookInfo = null;

    public function __construct(BookInfo $bookInfo)
    {
        $this->bookInfo = $bookInfo;
    }

    public function get($id)
    {
        return $this->bookInfo::find($id);
    }

    public function getWith($relation)
    {
        return $this->bookInfo::with($relation)->get();
    }

    public function getByBookCode($code) {
        return $this->bookInfo->where("code", $code)->first();
    }

    public function delete(&$self)
    {
        return $self->delete();
    }

    public function save(&$self)
    {
        return $self->save();
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->bookInfo, $method], $args);
    }
}