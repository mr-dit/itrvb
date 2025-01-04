<?php



namespace App\Commands;



use App\Repositories\PostsRepository;
use App\Post;
use App\Repositories\UsersRepository;
use App\Exceptions\UserNotFoundException;
use App\UUID;



class CreatePost

{

  private $postsRepository;

  private $usersRepository;



  public function __construct(PostsRepository $postsRepository, UsersRepository $usersRepository)

  {

    $this->postsRepository = $postsRepository;

    $this->usersRepository = $usersRepository;
  }


  public function handle(array $data): bool

  {

    if (!Uuid::isValid($data['author_uuid'])) {
      throw new \InvalidArgumentException('Invalid UUID');
    }



    $user = $this->usersRepository->get(new Uuid($data['author_uuid']));

    if (!$user) {

      throw new UserNotFoundException();
    }



    if (empty($data['title']) || empty($data['text'])) {

      throw new \InvalidArgumentException('Missing required fields');
    }

    $post = new Post(
      Uuid::random(),
      new Uuid($data['author_uuid']),
      $data['title'],
      $data['text']
    );


    $this->postsRepository->save($post);
    return true;
  }
}
