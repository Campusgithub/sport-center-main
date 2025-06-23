import React from 'react';

const SearchBar = ({ search, setSearch, onSearchClick }) => {
    return (
        <div className="flex w-full max-w-md">
            <input
                type="text"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                placeholder="Cari Lapangan"
                className="w-full px-4 py-2 rounded-l-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500"
            />
            <button
                onClick={onSearchClick}
                className="px-6 py-2 bg-gray-600 text-white rounded-r-lg hover:bg-gray-700"
            >
                Cari
            </button>
        </div>
    );
};

export default SearchBar;
