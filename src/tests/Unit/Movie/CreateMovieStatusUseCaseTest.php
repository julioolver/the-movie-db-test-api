
<?php

namespace Tests\Unit\Movie;

use App\Domain\Entities\User;
use App\Domain\Repositories\MovieApiRepositoryInterface;
use App\Domain\Repositories\MovieRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\UseCases\Movie\CreateMovieStatusUseCase;
use App\Dtos\UpdateMovieStatusDto;
use App\Infrastructure\Cache\MovieCacheService;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class CreateMovieStatusUseCaseTest extends TestCase
{
    protected MovieRepositoryInterface $movieRepository;
    protected UserRepositoryInterface $userRepository;
    protected CreateMovieStatusUseCase $useCase;
    protected MovieCacheService $movieCacheService;
    protected MovieApiRepositoryInterface $movieApiRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->movieRepository = Mockery::mock(MovieRepositoryInterface::class);
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->movieCacheService = Mockery::mock(MovieCacheService::class);
        $this->movieApiRepository = Mockery::mock(MovieApiRepositoryInterface::class);

        $this->useCase = new CreateMovieStatusUseCase(
            $this->movieRepository,
            $this->userRepository,
            $this->movieCacheService,
            $this->movieApiRepository
        );
    }

    public function testCreateMovieStatusSuccessfully(): void
    {
        Auth::shouldReceive('id')->andReturn(1);

        $user = new User(Auth::id(), 'john', 'john@example.com');
        $movie = Movie::factory()->makeMovie();

        $dto = new UpdateMovieStatusDto(
            externalId: '1234',
            provider: 'tmdb',
            title: 'Inception',
            director: 'Christopher Nolan',
            synopsis: 'A dream within a dream',
            duration: 120,
            year: 2010,
            posterPath: '/poster.jpg',
            watched: true,
            favorite: true,
            watchLater: false
        );

        $this->movieApiRepository
            ->shouldReceive('providesFullMovieDetails')
            ->once()
            ->andReturn(true);

        $this->movieRepository
            ->shouldReceive('findByExternalId')
            ->with($dto->externalId)
            ->once()
            ->andReturn(null);

        $this->movieRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn($movie);

        $this->userRepository
            ->shouldReceive('findById')
            ->with($user->getId())
            ->andReturn($user);

        $this->movieRepository
            ->shouldReceive('createUserMovieStatus')
            ->once()
            ->with($user, $movie, [
                'watched' => $dto->watched,
                'favorite' => $dto->favorite,
                'watch_later' => $dto->watchLater
            ]);

        $this->movieCacheService
            ->shouldReceive('clearUserMoviesCache')
            ->once()
            ->with($user->getId());

        $this->useCase->execute($dto);

        $this->assertTrue(true);
    }
}
