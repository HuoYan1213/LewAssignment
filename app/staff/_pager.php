<style>
.pager {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 30px;
}

.pager a {
    padding: 10px 22px;
    border-radius: 30px;
    background: linear-gradient(135deg, rgba(131,31,31,0.95), rgba(150,50,50,0.85));
    color: white;
    font-weight: 600;
    font-size: 16px;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(131,31,31,0.25);
    transition: all 0.3s ease, transform 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55);
}

.pager a:hover {
    background: linear-gradient(135deg, rgba(93,20,20,1), rgba(131,31,31,0.9));
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 8px 20px rgba(131,31,31,0.35);
}

.pager a.active {
    background: linear-gradient(135deg, rgb(93,20,20), rgb(131,31,31));
    color: #fff;
    box-shadow: 0 0 20px rgba(93,20,20,0.8);
}
</style>



<?php

class pager {
    public $limit;
    public $page;
    public $item_count;
    public $page_count;
    public $result;
    public $count;

    public function __construct($query, $params, $limit, $page) {
        global $_db;

        // Set limit and page
        $this->limit = ctype_digit($limit) ? max($limit, 1) : 10;
        $this->page = ctype_digit($page) ? max($page, 1) : 1;

        // Generate count SQL
        $countSQL = preg_replace('/SELECT.+FROM/i', 'SELECT COUNT(*) FROM', $query, 1);
        $stm = $_db->prepare($countSQL);
        $stm->execute($params);
        $this->item_count = $stm->fetchColumn();

        // Page count
        $this->page_count = max(ceil($this->item_count / $this->limit), 1);

        // Offset
        $offset = ($this->page - 1) * $this->limit;

        // Fetch current page data
        $stm = $_db->prepare($query . " LIMIT $offset, $this->limit");
        $stm->execute($params);
        $this->result = $stm->fetchAll();
        $this->count = count($this->result);
    }

    public function html($href = '', $attr = '') {
        if ($this->item_count == 0) {
            echo "<p style='text-align:center; color: #999; font-style: italic;'>No data found.</p>";
            return;
        }

        $prev = max($this->page - 1, 1);
        $next = min($this->page + 1, $this->page_count);

        echo "<nav class='pager' $attr>";
        echo "<a href='?page=1&$href'>First</a>";
        echo "<a href='?page=$prev&$href'>Previous</a>";

        for ($p = 1; $p <= $this->page_count; $p++) {
            $c = $p == $this->page ? 'active' : '';
            echo "<a href='?page=$p&$href' class='$c'>$p</a>";
        }

        echo "<a href='?page=$next&$href'>Next</a>";
        echo "<a href='?page=$this->page_count&$href'>Last</a>";
        echo "</nav>";
    }
}
