use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

public function store(Request $request)
{
    $admin = Admin::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return response()->json($admin);
}