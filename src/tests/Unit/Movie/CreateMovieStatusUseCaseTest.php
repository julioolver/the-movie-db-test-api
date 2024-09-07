<?php

namespace Tests\Unit\Movie;

use App\Domain\Entities\User;
use App\Domain\Repositories\MovieRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\UseCases\Movies\CreateMovieStatusUseCase;
use App\Dtos\UpdateMovieStatusDto;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class CreateMovieStatusUseCaseTest extends TestCase
{
    protected MovieRepositoryInterface $movieRepository;
    protected UserRepositoryInterface $userRepository;
    protected CreateMovieStatusUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->movieRepository = Mockery::mock(MovieRepositoryInterface::class);
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);

        $this->useCase = new CreateMovieStatusUseCase(
            $this->movieRepository,
            $this->userRepository
        );
    }

    public function testCreateMovieStatusSuccessfully(): void
    {
        Auth::shouldReceive('id')->andReturn(1);

        $user = new User(7, 'John Doe', 'john@example.com');
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
            isWatched: true,
            isFavorite: true,
            watchLater: false
        );

        $this->movieRepository
            ->shouldReceive('findByExternalId')
            ->with($dto->externalId)
            ->andReturn(null);

        $this->movieRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn($movie);

        $this->userRepository
            ->shouldReceive('findById')
            ->with(7)
            ->andReturn($user);

        $this->movieRepository
            ->shouldReceive('createUserMovieStatus')
            ->once()
            ->with($user, $movie, [
                'watched' => $dto->isWatched,
                'favorite' => $dto->isFavorite,
                'watch_later' => $dto->watchLater
            ]);

        $this->useCase->execute($dto);

        $this->assertTrue(true);
    }
}
