<?php

use App\Models\BlogPost;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\Forms\LiteralField;

class BlogPage extends Page
{

}

class BlogPageController extends PageController
{
    private static $allowed_actions = [
        'index',
        'detail'
    ];

    private static $url_handlers = [
        'detail/$ID' => 'detail'
    ];

    public function index(HTTPRequest $request)
    {
        $posts = BlogPost::get();

        // Pagination - 9 posts per halaman
        $paginatedPosts = PaginatedList::create($posts, $request)
            ->setPageLength(6);

        $data = [
            'Posts' => $paginatedPosts,
            'Title' => 'Daftar Blog'
        ];

        // Render pakai BlogPage.ss (utama) → fallback ke Page.ss
        return $this->customise($data)->renderWith(['BlogPage', 'Page']);
    }

    public function detail(HTTPRequest $request)
    {
        $id = $request->param('ID');
        $post = BlogPost::get()->byID($id);

        if (!$post) {
            return $this->httpError(404, 'Post tidak ditemukan');
        }

        return $this->customise([
            'Post' => $post,
            'Title' => $post->Title
        ])->renderWith(['BlogPageDetail', 'Page']);
    }

    public function getAllPosts()
    {
        return BlogPost::get();
    }

    public function getLatestPosts($limit = 5)
    {
        return BlogPost::get()->limit($limit);
    }
}
