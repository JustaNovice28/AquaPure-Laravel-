<div class="orders-section">
    <h5 class="fw-bold mb-3">
        <i class="bi bi-people-fill me-2"></i>Manage Cashier Accounts
    </h5>

    <div class="row">
        {{-- Add new cashier form --}}
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Add New Cashier</h6>
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="newUsername" class="form-label fw-semibold">Username</label>
                            <input type="text" name="username" id="newUsername"
                                   class="form-control" placeholder="Enter username"
                                   value="{{ old('username') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" id="newPassword"
                                   class="form-control" placeholder="Min. 6 characters"
                                   minlength="6" required>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-person-plus me-1"></i> Create Cashier
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Cashier list --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Cashier Accounts ({{ $cashiers->count() }})</h6>
                    @if($cashiers->isEmpty())
                        <p class="text-muted">No cashier accounts yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover admin-table mb-0">
                                <thead>
                                    <tr class="table-header">
                                        <th>Username</th>
                                        <th>Created</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cashiers as $cashier)
                                    <tr>
                                        <td class="fw-semibold">{{ $cashier->username }}</td>
                                        <td class="text-muted small">{{ $cashier->created_at->format('M d, Y') }}</td>
                                        <td class="text-end">
                                            <form action="{{ route('admin.users.delete', $cashier->id) }}"
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Delete cashier \'{{ $cashier->username }}\'?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>