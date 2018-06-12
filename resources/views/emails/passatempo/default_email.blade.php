@component('mail::message')
# Introduction

Wanna buy something?

You've made a total of {{ $user->orders_count }} orders.

@component('mail::button', ['url' => ''])
Go and buy :D
@endcomponent

Thanks,<br>
Passatempo
@endcomponent
