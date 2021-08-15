<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Division;
use App\Models\Poll;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $polls = Poll::with(['choice', 'choice.votes', 'choice.votes.division'])->get();
        $choices = $this->countTotalPoinDivison();
        $total_choices = array_sum(array_column($choices, "total_poin"));

        foreach ($polls as $index => $item) {
            foreach ($item->choice as $index_c => $item_c) {
                foreach ($choices as $index_co => $item_co) {
                    if ($index_co == $item_c['id']) {
                        $polls[$index]->choice[$index_c]->poin = $item_co['total_poin'] / $total_choices * 100;
                    }
                }
            }
        }

        $user = Auth::user();
        return view('home', compact("polls", "user"), [
            'title' => 'Home'
        ]);
    }

    private function countTotalPoinDivison()
    {
        $division = Division::with('votes')->get();   //get division with votes
        $division_poin = [];  //inisialisasi division poin
        foreach ($division as $index => $item) {   //perulangan pertama untuk data divisi dengan votes
            $division_choice = []; //menampung data choice dari perdivisi
            foreach ($item->votes as $index_v => $item_v) {   //perulangan untuk data votes per divisi
                $poin = 0;
                if (!in_array($item_v->choice_id, array_column($division_choice, "choice_id"))) {   //jika didalam array division_choice['choice_id'] itu belum ada
                    $poin += 1;
                    $division_choice[] = ["choice_id" => $item_v->choice_id, "poll_id" => $item_v->poll_id, "division_id" => $item_v->division_id, "poin" => $poin];
                } else {
                    $index = array_search($item_v->choice_id, array_column($division_choice, "choice_id"));
                    $poin = $division_choice[$index]['poin'] + 1;
                    $division_choice[$index] = ["choice_id" => $item_v->choice_id, "poll_id" => $item_v->poll_id, "division_id" => $item_v->division_id, "poin" => $poin];
                }
            }

            if (!empty($division_choice)) {
                $highest_index = array_keys(array_column($division_choice, 'poin'), max(array_column($division_choice, 'poin')));  //mencari index array berdasarkan poin terbesar didalam array tersebut
                $highest_division_choice = []; 
                $poin = 0;
                foreach ($division_choice as $index_c => $item_c) {  //perulangan division choice
                    // $poin = 1;
                    if(count($highest_index) == 1){  
                        if ($index_c == $highest_index[0]) {
                            $poin = $item_c['poin'];
                            $highest_division_choice[] = ["poll_id" => $item_c['poll_id'], "choice_id" => $item_c['choice_id'], "poin" => count($item->votes) / count($item->votes)];  //kalkulasi per choice
                        }
                    }else{
                        $poin = $item_c['poin'];
                        $highest_division_choice[] = ["poll_id" => $item_c['poll_id'], "choice_id" => $item_c['choice_id'], "poin" => $item_c['poin'] / count($item->votes)];
                    }
                }
                $division_choice = $highest_division_choice;
            }
            $division_poin[] = [
                "divison_name" => $item->name,
                "division_id" => $item->id,
                "divison_choice" => $division_choice
            ];
        }
        $choices = [];
        foreach ($division_poin as $index_p => $item_p) {     
            if (!empty($item_p['divison_choice'])) {
                foreach ($item_p['divison_choice'] as $index_c => $item_c) {
                    $total_poin = 0;
                    if (!isset($choices[$item_c['choice_id']])) {
                        $total_poin += $item_c['poin'];
                        $choices[$item_c['choice_id']] = [
                            "poll_id" => $item_c['poll_id'],
                            "choice_id" => $item_c['choice_id'],
                            "total_poin" => $total_poin, 
                        ];
                    } else {
                        $total_poin = $choices[$item_c['choice_id']]['total_poin'] + $item_c['poin'];
                        $choices[$item_c['choice_id']]['total_poin'] = $total_poin;
                    }
                }
            }
        }
        return $choices;
    }
}
