<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Please fill the username")
     * @Assert\NotNull(message="This field is mandaory")
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @Assert\NotBlank(message="Please fill the email")
     * @Assert\NotNull(message="This field is mandaory")
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $userEmail;

    /**
     * @Assert\NotBlank(message="Please fill the username")
     * @Assert\NotNull(message="This field is mandaory")
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Blog::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $blog;

    /**
     * @ORM\Column(type="boolean")
     */
    private $valid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(string $userEmail): self
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

        return $this;
    }

    public function getValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @Assert\Callback()
     *
     * @param [type] $payload
     *
     * @return void
     */
    public function invalidUsername(ExecutionContextInterface $context, $payload)
    {
        if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $this->getUsername())) {
            $message = 'The username is invalid, username must start with a letter';
            $context->buildViolation($message)
                ->atPath('username')
                ->addViolation();
        }
    }

    /**
     * @Assert\Callback()
     *
     * @param  $payload
     *
     * @return void
     */
    public function invalidContent(ExecutionContextInterface $context, $payload)
    {
        if (!preg_match('/^[A-Za-z][A-Za-z0-9[:punct:] ]{2,300}$/', $this->getContent())) {
            $message = 'The comment should at least contain two words';
            $context->buildViolation($message)
                ->atPath('content')
                ->addViolation();
        }
    }
}
