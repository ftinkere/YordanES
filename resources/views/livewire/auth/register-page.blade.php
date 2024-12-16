<div>
    <form wire:submit="register">
        <label>Username</label>
        <input type="text" wire:model="username">
        <label>Visible Name</label>
        <input type="text" wire:model="visible_name">
        <label>Email</label>
        <input type="email" wire:model="email">
        <label>Password</label>
        <input type="password" wire:model="password">
        <input type="password" wire:model="password_repeat">

        <button type="submit">Рега</button>
    </form>
</div>
