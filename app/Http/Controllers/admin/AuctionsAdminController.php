<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\User;
use App\Models\Post;
use App\Models\Lesson;
use App\Events\NewNotification;
use App\Http\Controllers\Enum\MessageEnum;
use App\Models\order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Route;
class AuctionsAdminController extends Controller
{
    
    public function showAdminAuction(){
      
        $auctions = Auction::with(['auction_post','userOwner','userAw'])->get();
        $posts=Post::with(['auctions.userOwner','auctions.userAw','auctions'])->get();
        $route = Route::current()->getName();
        if($route == 'admin_acution'){
            return view('admin.adminManageAuction', [
                'auctions'   => $auctions,
            ]);
        }elseif($route == 'endede_acution'){
            $posts=Post::with(['auctions', 'users'])->get();
            $order = order::With(['post.auctions','post.users','user'])->get();
           
            return view('admin.adminManageEndedAuction', [
                'posts'   => $posts,
                'orders'   => $order,
            ]);
        }elseif($route == 'un_complate'){
           
            $posts=Post::with(['auctions', 'users'])->get();
            return view('admin.adminManageUncomplateAuction', [
                'posts'   => $posts,
                'auctions'   => $auctions,
            ]);
        }
    }

    public function uneditActive_auction(Request $request){
        $id = $request->auction_id;
        $userid = $request->userid;
        $user = User::find($userid);
        if($request->is_active==1){
            $lesson = $this->lessonNotification($user->id, 'تم الغاء الموافقة على عملية المزايدة ', Auth::user()->name, 'admin_wallet');
            try{
                $pusher = $this->pusherNotifications($user);
                $active = Auction::where('id', $id)->update(['is_active' => 0]);
                return redirect('admin_acution')
                ->with(['success'=>MessageEnum::MESSAGE_UPDATE_SUCCESS]);
            }catch(\Exception $e){
                return back()->with(['error'=>MessageEnum::MESSAGE_UPDATE_ERROR]);
            }
        }else{
            
            $lesson = $this->lessonNotification($user->id, 'تم الموافقة على عملية المزايدة ', Auth::user()->name, 'admin_wallet');
            try{
                $pusher = $this->pusherNotifications($user);
                $active = Auction::where('id', $id)->update(['is_active' => 1]);
                return redirect('admin_acution')
                ->with(['success'=>MessageEnum::MESSAGE_UPDATE_SUCCESS]);
            }catch(\Exception $e){
                return back()->with(['error'=>MessageEnum::MESSAGE_UPDATE_ERROR]);
            }   
         }
    }

    public function admin_orders(Request $request){
   
        $admin = User::with(['roles' => function($q){
            $q->where('name', 'admin');
        }])->first();
         $seller=User::find($request->userid);
         $buyer=User::find($request->buyer);
         $admin_ratio=$request->admin_ratio;
         $order_price=$request->order_price;
         $post_id=$request->post_id; 
         $admin->forceTransfer($seller, $order_price);
         $order= new Order();
          $order->price=$order_price;
          $order->user_id=$buyer->id;
          $order->post_id= $post_id;
          $order->admin_ratio=$admin_ratio;
          $order->is_active= 1;
          if($order->save())
         $active = Post::where('id',$post_id)->update(['status_auction' => 1]);
         return redirect('un_complate');
    }

    public function showAdminStartAuction(){
        return view('admin.adminManageStartedAuction');
    }

    public function showAdminEndedAuction(){
        return view('admin.adminManageEndedAuction');
    }
    public function editActive(Request $request){
       $user=$request->user;
      
        $auction_id = $request->auction_id;
        $active = Auction::where('id', $auction_id)->update(['admin_confirm' => 1]);
        $post_id= $request->post_id;
        $post_price=Post::find($post_id);
        $post_price=$post_price->starting_price;
        $discount=$post_price*20/100;
        $userAdmin = $this->roleUsers();
        $users=Auction::with('userAw')->where('post_id',$post_id)->where('aw_user_id','!=',$user)->get();
     foreach($users as $user)
       {    
            $us=User::find($user->userAw->id);
            $wallet = $this->walletTransfer($userAdmin, $us, $us->name, $discount, 'تم سحب مبلغ من حساب');
            $lesson = $this->lessonNotification($us->id, 'لقد تمت عملية ايداع الى حسابك ', '', 'wallet/"'.$us->id.'"');
            $notify = $this->pusherNotifications($us);
       }
      
     
       if($active){
            $user = User::find($request->userid);
            $lesson = $this->lessonNotification($user->id, 'تم بيع سيارتك يمكنك التواصل مع المشتري', '', 'chat/ "'.$post_id.'"'); 
            $notify = $this->pusherNotifications($user);
            $user = User::find($request->user);
            $lesson = $this->lessonNotification($user->id, 'لقد ربحت في المزاد يمكنك الان التواصل مع البائع', '', 'chat/ "'.$post_id.'"'); 
            $notify = $this->pusherNotifications($user);
            return redirect('un_complate')
            ->with(['success'=>'تم الموافقة بنجاح']);
        }else{
            return back()->with(['error'=>'خطاء هناك مشكلة في عملية الموافقة على المزاد']);
        }
    }
} 