module CustomHelpers
  def home_link
    link_to "Home", config[:host]
  end

  def full_title(page_title)
    base_title = "ダブルメガネ株式会社"
    if page_title.nil?
      base_title
    else
      "#{page_title} - #{base_title}"
    end
  end
end