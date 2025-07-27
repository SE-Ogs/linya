use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CommentController extends Controller
{
    protected $commentsPath = 'storage/app/comments.txt';

    public function index(Request $request)
    {
        $comments = $this->loadComments();

        $sort = $request->query('sort', 'newest');

        // Sort comments
        usort($comments, function ($a, $b) use ($sort) {
            return match ($sort) {
                'newest' => $b['timestamp'] <=> $a['timestamp'],
                'oldest' => $a['timestamp'] <=> $b['timestamp'],
                'most_liked' => $b['likes'] <=> $a['likes'],
                default => 0,
            };
        });

        // Enrich comments with replies and time
        foreach ($comments as &$comment) {
            $comment['time_ago'] = Carbon::createFromTimestamp($comment['timestamp'])->diffForHumans();
            $comment['replies'] = array_values(array_filter($comments, fn($c) => $c['parent_id'] === $comment['id']));
            $comment['reply_count'] = count($comment['replies']);
        }

        return view('comments', [
            'comments' => $comments,
            'sort' => $sort
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'text' => 'required|string',
        ]);

        $comments = $this->loadComments();

        $newComment = [
            'id' => uniqid(),
            'username' => $request->input('username'),
            'text' => $request->input('text'),
            'timestamp' => time(),
            'parent_id' => $request->input('parent_id', null),
            'likes' => 0,
            'dislikes' => 0,
        ];

        $comments[] = $newComment;
        $this->saveComments($comments);

        return redirect()->route('comments.index');
    }

     private function loadComments(): array
    {
        if (!File::exists($this->commentsPath)) {
            return [];
        }

        return json_decode(File::get($this->commentsPath), true) ?? [];
    }

    private function saveComments(array $comments): void
    {
        File::put($this->commentsPath, json_encode($comments, JSON_PRETTY_PRINT));
    }
}
