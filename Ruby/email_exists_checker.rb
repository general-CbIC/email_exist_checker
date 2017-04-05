class EMAIL_EXISTS_CHECKER
  def check_email(email)
  	domain = get_domain(email)
  	mx_server = `dig #{domain} MX +short`.split()[1]
  	return false if !mx_server
  	return validate(check_by_telnet(mx_server, email, domain))
  end

  def get_domain(email)
    at_index = email.index('@')
    return false if !at_index
    return email[at_index+1..-1]
  end

  def check_by_telnet(mx_host, email, domain)
  	com = "(echo \"HELO #{domain}\"; "
  	com += 'sleep 1; echo "MAIL FROM: <no-reply@gmail.com>"; sleep 1; '
  	com += "echo \"RCPT TO: <#{email}>; sleep 2;) | "
  	com += "telnet #{mx_host} 25"
  	return `#{com}`
  end

  def validate(str)
  	return str.split().include? "220"
  end
end
