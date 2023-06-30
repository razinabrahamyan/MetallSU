<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Post;
use App\Services\Contracts\PostContract;
use Illuminate\Support\Facades\Validator;

class PostService implements PostContract
{

    public function index(): array
    {
        $posts = Post::with(['images','creator'])->get();
        return ['posts' => $posts];
    }

    public function store($request): \Illuminate\Http\RedirectResponse
    {

        $this->validator($request->all());

        $imageNames = [];
        $work_hours = [];
        $prices = [];

        foreach ($request->prices as $price){
            $subPrices = [];
            foreach ($price as $i => $subprice){
                if ($i !== 'category') {
                    $subPrices[] = $subprice;
                }
            }
            $prices[] = [
                'category' => $price['category'],
                'prices' => $subPrices
            ] ;

        }

        foreach ($request->work_times as $day => $time){
            $work_hours[$day] =  [
                'start' => [
                    'hour' => $time[0][0],
                    'minute' => $time[0][1]
                ],
                'end' => [
                    'hour' => $time[1][0],
                    'minute' => $time[1][1]
                ]
            ];

        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'address' => ['address' => $request->address,'lat' => $request->lat, 'lng' => $request->lng],
            'work_hours' => $work_hours,
            'prices' => $prices

        ]);
        if($request->images[0]){
            $destinationPath = storage_path('/app/public/user/images/post');
            foreach ($request->images as $image){
                $current_image = $image;
                $image_name = time().'_'.$current_image->getClientOriginalName();
                $current_image->move($destinationPath, $image_name);
                array_push($imageNames,['name' => $image_name,'post_id' => $post->id]);
            }
            Image::insert($imageNames);
        }
        return redirect()->route('map.posts');
    }

    public function show($id): array
    {
        $post = Post::where('id',$id)->with('images')->first();
        $week_day_translations = ['Monday' => 'Понедельник','Tuesday'=> 'Вторник','Wednesday'=> 'Среда','Thursday'=> 'Четверг','Friday'=> 'Пятница','Saturday'=> 'Суббота','Sunday'=> 'Воскресение'];
        if($post){
            return ['post' => $post, 'week_day_translations' => $week_day_translations];
        }
        return redirect()->route('home');

    }

    public function edit($id)
    {
        // TODO: Implement edit() method.
    }

    public function update($request, $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }


    /**
     * @param array $data
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validator(array $data): array
    {
        return Validator::make($data,[
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:600'
        ])->validate();
    }

}
